import React, { useState } from 'react';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import {
  ActivityIndicator,
  Alert,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { api, apiErrorMessage } from '../api/client';
import { useAuth } from '../context/AuthContext';
import { colors } from '../theme/colors';
import type { ProfileStackParamList } from '../navigation/types';

type Props = NativeStackScreenProps<ProfileStackParamList, 'ProfileHome'>;

export default function ProfileScreen({ navigation }: Props) {
  const { user, refreshUser, logout } = useAuth();
  const [name, setName] = useState(user?.name ?? '');
  const [designation, setDesignation] = useState(user?.designation ?? '');
  const [mobile, setMobile] = useState(user?.mobile ?? '');
  const [email, setEmail] = useState(user?.email ?? '');
  const [saving, setSaving] = useState(false);

  async function handleSave() {
    setSaving(true);
    try {
      await api.put('/profile', { name, designation, mobile, email });
      await refreshUser();
      Alert.alert('सफल', 'Profile बदल दिया गया है');
    } catch (error) {
      Alert.alert('त्रुटि', apiErrorMessage(error));
    } finally {
      setSaving(false);
    }
  }

  function handleLogout() {
    Alert.alert('लॉग आउट', 'क्या आप लॉग आउट करना चाहते हैं?', [
      { text: 'रद्द करें', style: 'cancel' },
      { text: 'लॉग आउट', style: 'destructive', onPress: logout },
    ]);
  }

  return (
    <ScrollView style={styles.screen} contentContainerStyle={{ padding: 16 }}>
      <View style={styles.card}>
        <Text style={styles.loginId}>{user?.login_id}</Text>
        <Text style={styles.role}>{user?.user_role_name} · {user?.office_name}</Text>
      </View>

      <Text style={styles.label}>नाम</Text>
      <TextInput style={styles.input} value={name} onChangeText={setName} />

      <Text style={styles.label}>पदनाम</Text>
      <TextInput style={styles.input} value={designation ?? ''} onChangeText={setDesignation} />

      <Text style={styles.label}>मोबाइल</Text>
      <TextInput style={styles.input} value={mobile ?? ''} onChangeText={setMobile} keyboardType="phone-pad" />

      <Text style={styles.label}>ईमेल</Text>
      <TextInput style={styles.input} value={email ?? ''} onChangeText={setEmail} keyboardType="email-address" autoCapitalize="none" />

      <TouchableOpacity style={styles.saveButton} onPress={handleSave} disabled={saving}>
        {saving ? <ActivityIndicator color={colors.white} /> : <Text style={styles.saveButtonText}>सहेजें</Text>}
      </TouchableOpacity>

      <TouchableOpacity style={styles.linkButton} onPress={() => navigation.navigate('ChangePassword')}>
        <Text style={styles.linkButtonText}>पासवर्ड बदलें</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
        <Text style={styles.logoutButtonText}>लॉग आउट</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  screen: { backgroundColor: colors.background },
  card: {
    backgroundColor: colors.primaryLight,
    borderRadius: 12,
    padding: 18,
    marginBottom: 20,
  },
  loginId: { color: colors.white, fontSize: 18, fontWeight: '700' },
  role: { color: '#dbe9fb', marginTop: 4 },
  label: { fontSize: 13, color: colors.muted, marginBottom: 6, marginTop: 12 },
  input: {
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: 10,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 15,
  },
  saveButton: {
    backgroundColor: colors.primary,
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 24,
  },
  saveButtonText: { color: colors.white, fontWeight: '700' },
  linkButton: { alignItems: 'center', marginTop: 16 },
  linkButtonText: { color: colors.primaryLight, fontWeight: '600' },
  logoutButton: {
    backgroundColor: colors.danger,
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 24,
    marginBottom: 40,
  },
  logoutButtonText: { color: colors.white, fontWeight: '700' },
});
