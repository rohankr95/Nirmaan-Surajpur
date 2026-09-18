import React, { useEffect, useState } from 'react';
import * as ImagePicker from 'expo-image-picker';
import * as Location from 'expo-location';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import {
  ActivityIndicator,
  Alert,
  Image,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { api, apiErrorMessage } from '../api/client';
import { WorkStage } from '../api/types';
import { colors } from '../theme/colors';
import type { WorkStackParamList } from '../navigation/types';

type Props = NativeStackScreenProps<WorkStackParamList, 'ProgressUpdate'>;

export default function ProgressUpdateScreen({ route, navigation }: Props) {
  const { workId, stageId, stageName } = route.params;
  const [stages, setStages] = useState<WorkStage[]>([]);
  const [selectedStage, setSelectedStage] = useState<number | undefined>(stageId);
  const [estimatedDate, setEstimatedDate] = useState('');
  const [amount, setAmount] = useState('');
  const [remark, setRemark] = useState('');
  const [photos, setPhotos] = useState<{ uri: string; fileName: string; mimeType: string }[]>([]);
  const [coords, setCoords] = useState<{ latitude: number; longitude: number } | null>(null);
  const [locating, setLocating] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    if (!stageId) {
      api.get<{ stages: WorkStage[] }>(`/works/${workId}/stages`).then(({ data }) => {
        setStages(data.stages);
        setSelectedStage(data.stages[0]?.work_type_stage_id);
      });
    }
  }, [workId, stageId]);

  async function captureLocation() {
    setLocating(true);
    try {
      const { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        Alert.alert('अनुमति आवश्यक', 'कार्य स्थल दर्ज करने के लिए लोकेशन की अनुमति दें');
        return;
      }
      const position = await Location.getCurrentPositionAsync({});
      setCoords({ latitude: position.coords.latitude, longitude: position.coords.longitude });
    } catch {
      Alert.alert('त्रुटि', 'लोकेशन प्राप्त नहीं हो सकी');
    } finally {
      setLocating(false);
    }
  }

  async function addPhoto(fromCamera: boolean) {
    const permission = fromCamera
      ? await ImagePicker.requestCameraPermissionsAsync()
      : await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (permission.status !== 'granted') {
      Alert.alert('अनुमति आवश्यक', 'फोटो जोड़ने के लिए अनुमति आवश्यक है');
      return;
    }
    const result = fromCamera
      ? await ImagePicker.launchCameraAsync({ quality: 0.6 })
      : await ImagePicker.launchImageLibraryAsync({ quality: 0.6 });
    if (!result.canceled && result.assets?.[0]) {
      const asset = result.assets[0];
      // A picker-supplied blob: URI carries no filename of its own -- fall
      // back to a guessed name/type only when the asset genuinely has none
      // (native camera captures sometimes omit fileName/mimeType).
      const mimeType = asset.mimeType ?? 'image/jpeg';
      const extFromMime = mimeType.split('/').pop() ?? 'jpg';
      const fileName = asset.fileName ?? `photo_${Date.now()}.${extFromMime}`;
      setPhotos((prev) => [...prev, { uri: asset.uri, fileName, mimeType }]);
    }
  }

  async function handleSubmit() {
    if (!selectedStage) {
      Alert.alert('चरण चुनें', 'कृपया कार्य प्रगति चरण चुनें');
      return;
    }
    if (photos.length === 0) {
      Alert.alert('फोटो आवश्यक', 'कम से कम एक फोटो जोड़ें');
      return;
    }
    setSubmitting(true);
    try {
      const form = new FormData();
      form.append('mb_stages', String(selectedStage));
      if (estimatedDate) form.append('estimated_completion_date', estimatedDate);
      if (amount) form.append('expenditure_amount', amount);
      if (remark) form.append('description', remark);
      if (coords) {
        form.append('latitude', String(coords.latitude));
        form.append('longitude', String(coords.longitude));
      }
      for (const photo of photos) {
        if (Platform.OS === 'web') {
          // RN's {uri, name, type} multipart shorthand is a native fetch-shim
          // feature -- a real browser's FormData needs an actual Blob/File,
          // and a blob: URI carries no filename/extension of its own.
          const blob = await (await fetch(photo.uri)).blob();
          form.append('files[]', blob, photo.fileName);
        } else {
          form.append('files[]', { uri: photo.uri, name: photo.fileName, type: photo.mimeType } as any);
        }
      }

      await api.post(`/works/${workId}/progress`, form, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      Alert.alert('सफल', 'प्रगति सफलतापूर्वक दर्ज की गई', [
        { text: 'ठीक है', onPress: () => navigation.goBack() },
      ]);
    } catch (error) {
      Alert.alert('त्रुटि', apiErrorMessage(error));
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <ScrollView style={styles.screen} contentContainerStyle={{ padding: 16 }}>
      <Text style={styles.label}>कार्य प्रगति चरण</Text>
      {stageId ? (
        <View style={styles.readonlyField}>
          <Text style={styles.readonlyText}>{stageName}</Text>
        </View>
      ) : (
        <View style={styles.pickerWrap}>
          <Picker selectedValue={selectedStage} onValueChange={(v) => setSelectedStage(v)}>
            {stages.map((s) => (
              <Picker.Item key={s.work_type_stage_id} label={`${s.stage_number}. ${s.stage_name}`} value={s.work_type_stage_id} />
            ))}
          </Picker>
        </View>
      )}

      <Text style={styles.label}>पूर्ण होने की अनुमानित तिथि (YYYY-MM-DD)</Text>
      <TextInput
        style={styles.input}
        placeholder="2026-10-01"
        value={estimatedDate}
        onChangeText={setEstimatedDate}
      />

      <Text style={styles.label}>व्यय राशि</Text>
      <TextInput style={styles.input} placeholder="₹" keyboardType="numeric" value={amount} onChangeText={setAmount} />

      <Text style={styles.label}>टिप्पणी</Text>
      <TextInput
        style={[styles.input, styles.multiline]}
        placeholder="टिप्पणी दर्ज करें"
        multiline
        value={remark}
        onChangeText={setRemark}
      />

      <Text style={styles.label}>कार्य स्थल की लोकेशन</Text>
      <TouchableOpacity style={styles.secondaryButton} onPress={captureLocation} disabled={locating}>
        {locating ? (
          <ActivityIndicator color={colors.primary} />
        ) : (
          <Text style={styles.secondaryButtonText}>
            {coords ? `📍 ${coords.latitude.toFixed(4)}, ${coords.longitude.toFixed(4)}` : 'वर्तमान लोकेशन प्राप्त करें'}
          </Text>
        )}
      </TouchableOpacity>

      <Text style={styles.label}>फोटो / बिल संलग्न करें</Text>
      <View style={styles.photoRow}>
        {photos.map((photo) => (
          <Image key={photo.uri} source={{ uri: photo.uri }} style={styles.photoThumb} />
        ))}
      </View>
      <View style={styles.photoButtons}>
        <TouchableOpacity style={styles.secondaryButton} onPress={() => addPhoto(true)}>
          <Text style={styles.secondaryButtonText}>📷 कैमरा</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.secondaryButton} onPress={() => addPhoto(false)}>
          <Text style={styles.secondaryButtonText}>🖼️ गैलरी</Text>
        </TouchableOpacity>
      </View>

      <TouchableOpacity style={styles.submitButton} onPress={handleSubmit} disabled={submitting}>
        {submitting ? <ActivityIndicator color={colors.white} /> : <Text style={styles.submitButtonText}>सबमिट करें</Text>}
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  screen: { backgroundColor: colors.background },
  label: { fontSize: 13, color: colors.muted, marginTop: 16, marginBottom: 6 },
  input: {
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: 10,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 15,
  },
  multiline: { minHeight: 80, textAlignVertical: 'top' },
  readonlyField: {
    backgroundColor: colors.cardAlt,
    borderRadius: 10,
    paddingHorizontal: 14,
    paddingVertical: 12,
  },
  readonlyText: { fontSize: 15, fontWeight: '600', color: colors.text },
  pickerWrap: { backgroundColor: colors.white, borderRadius: 10, borderWidth: 1, borderColor: colors.border },
  secondaryButton: {
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.primaryLight,
    borderRadius: 10,
    paddingVertical: 12,
    alignItems: 'center',
    flex: 1,
    marginHorizontal: 4,
  },
  secondaryButtonText: { color: colors.primaryLight, fontWeight: '600' },
  photoRow: { flexDirection: 'row', flexWrap: 'wrap', marginBottom: 8 },
  photoThumb: { width: 70, height: 70, borderRadius: 8, marginRight: 8, marginBottom: 8 },
  photoButtons: { flexDirection: 'row', marginHorizontal: -4 },
  submitButton: {
    backgroundColor: colors.accent,
    borderRadius: 10,
    paddingVertical: 16,
    alignItems: 'center',
    marginTop: 24,
    marginBottom: 40,
  },
  submitButtonText: { color: colors.white, fontWeight: '700', fontSize: 16 },
});
