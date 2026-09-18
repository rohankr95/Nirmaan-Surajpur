import React, { useCallback, useState } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import {
  ActivityIndicator,
  Linking,
  RefreshControl,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import { api } from '../api/client';
import { WorkDetail, WorkStage } from '../api/types';
import { colors } from '../theme/colors';
import type { WorkStackParamList } from '../navigation/types';

type Props = NativeStackScreenProps<WorkStackParamList, 'WorkDetail'>;

export default function WorkDetailScreen({ route, navigation }: Props) {
  const { workId, workName } = route.params;
  const [work, setWork] = useState<WorkDetail | null>(null);
  const [stages, setStages] = useState<WorkStage[]>([]);
  const [loading, setLoading] = useState(true);

  const load = useCallback(async () => {
    try {
      const [workRes, stagesRes] = await Promise.all([
        api.get<{ work: WorkDetail }>(`/works/${workId}`),
        api.get<{ stages: WorkStage[] }>(`/works/${workId}/stages`),
      ]);
      setWork(workRes.data.work);
      setStages(stagesRes.data.stages);
    } finally {
      setLoading(false);
    }
  }, [workId]);

  useFocusEffect(
    useCallback(() => {
      navigation.setOptions({ title: workName });
      load();
    }, [load, navigation, workName])
  );

  if (loading && !work) {
    return (
      <View style={styles.center}>
        <ActivityIndicator color={colors.primary} />
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.screen}
      contentContainerStyle={{ padding: 16 }}
      refreshControl={<RefreshControl refreshing={loading} onRefresh={load} />}
    >
      {work && (
        <View style={styles.summaryCard}>
          <Text style={styles.workName}>{work.work_name}</Text>
          <Row label="कार्य का प्रकार" value={work.work_type_name} />
          <Row label="योजना" value={work.scheme_name} />
          <Row label="राशि" value={`₹${work.sanction_amount.toLocaleString('en-IN')}`} />
          <Row label="कार्य एजेंसी" value={work.office_name} />
          <Row label="क्षेत्र" value={work.area} />
          <Row label="जिम्मेदार अधिकारी" value={work.employee_name} />
        </View>
      )}

      <Text style={styles.sectionTitle}>कार्य प्रगति चरण</Text>
      {stages.map((stage) => (
        <View key={stage.work_type_stage_id} style={styles.stageCard}>
          <View style={styles.stageHeader}>
            <Text style={styles.stageNumber}>{stage.stage_number}. चरण — {stage.stage_name}</Text>
            <Text style={styles.percent}>पूर्ण {stage.percent_complete ?? 0}%</Text>
          </View>
          {stage.recorded ? (
            <>
              <Row label="पूर्ण होने की अनुमानित तिथि" value={stage.estimated_completion_date ?? '-'} />
              <Row
                label="व्यय राशि"
                value={stage.expenditure_amount != null ? `₹${stage.expenditure_amount.toLocaleString('en-IN')}` : '-'}
              />
              {stage.description ? <Row label="टिप्पणी" value={stage.description} /> : null}
              {stage.photos.length > 0 && (
                <TouchableOpacity style={styles.fileButton} onPress={() => Linking.openURL(stage.photos[0])}>
                  <Text style={styles.fileButtonText}>फाइल देखें</Text>
                </TouchableOpacity>
              )}
            </>
          ) : (
            <Text style={styles.notRecorded}>अभी कोई अपडेट दर्ज नहीं</Text>
          )}
          <TouchableOpacity
            style={styles.updateButton}
            onPress={() =>
              navigation.navigate('ProgressUpdate', {
                workId,
                workName,
                stageId: stage.work_type_stage_id,
                stageName: stage.stage_name,
              })
            }
          >
            <Text style={styles.updateButtonText}>प्रोग्रेस अपडेट करे</Text>
          </TouchableOpacity>
        </View>
      ))}
    </ScrollView>
  );
}

function Row({ label, value }: { label: string; value: string }) {
  return (
    <View style={styles.row}>
      <Text style={styles.rowLabel}>{label}</Text>
      <Text style={styles.rowValue}>{value}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  screen: { backgroundColor: colors.background },
  center: { flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: colors.background },
  summaryCard: {
    backgroundColor: colors.white,
    borderRadius: 12,
    padding: 16,
    marginBottom: 20,
    elevation: 1,
  },
  workName: { fontSize: 17, fontWeight: '700', color: colors.text, marginBottom: 10 },
  row: { flexDirection: 'row', justifyContent: 'space-between', paddingVertical: 4 },
  rowLabel: { color: colors.muted, fontSize: 13, flex: 1 },
  rowValue: { color: colors.text, fontSize: 13, fontWeight: '600', flex: 1, textAlign: 'right' },
  sectionTitle: { fontSize: 16, fontWeight: '700', color: colors.text, marginBottom: 10 },
  stageCard: {
    backgroundColor: colors.cardAlt,
    borderRadius: 12,
    padding: 14,
    marginBottom: 14,
  },
  stageHeader: {
    borderBottomWidth: 1,
    borderBottomColor: '#c3d7ef',
    paddingBottom: 8,
    marginBottom: 8,
  },
  stageNumber: { fontSize: 13, color: colors.text, fontWeight: '600', marginBottom: 4 },
  percent: { textAlign: 'center', fontSize: 18, fontWeight: '700', color: colors.primaryLight },
  notRecorded: { color: colors.muted, fontStyle: 'italic', marginBottom: 8 },
  fileButton: {
    backgroundColor: colors.primaryLight,
    borderRadius: 20,
    paddingVertical: 8,
    alignItems: 'center',
    marginTop: 8,
  },
  fileButtonText: { color: colors.white, fontWeight: '600', fontSize: 13 },
  updateButton: {
    backgroundColor: colors.accent,
    borderRadius: 20,
    paddingVertical: 10,
    alignItems: 'center',
    marginTop: 10,
  },
  updateButtonText: { color: colors.white, fontWeight: '700', fontSize: 13 },
});
