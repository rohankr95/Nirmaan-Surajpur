import React, { useCallback, useState } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import {
  ActivityIndicator,
  FlatList,
  Image,
  RefreshControl,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import { api } from '../api/client';
import { WorkSummary } from '../api/types';
import { colors } from '../theme/colors';
import type { WorkStackParamList } from '../navigation/types';
import LocationPinPlaceholder from '../components/LocationPinPlaceholder';

type Props = NativeStackScreenProps<WorkStackParamList, 'WorkList'>;

export default function WorkListScreen({ navigation }: Props) {
  const [works, setWorks] = useState<WorkSummary[]>([]);
  const [loading, setLoading] = useState(true);

  const load = useCallback(async () => {
    try {
      const { data } = await api.get<{ works: WorkSummary[] }>('/works');
      setWorks(data.works);
    } finally {
      setLoading(false);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
      load();
    }, [load])
  );

  if (loading && works.length === 0) {
    return (
      <View style={styles.center}>
        <ActivityIndicator color={colors.primary} />
      </View>
    );
  }

  return (
    <FlatList
      style={styles.screen}
      contentContainerStyle={{ padding: 16 }}
      data={works}
      keyExtractor={(item) => String(item.work_id)}
      refreshControl={<RefreshControl refreshing={loading} onRefresh={load} />}
      ListEmptyComponent={
        <Text style={styles.empty}>अभी कोई कार्य प्रगति पर नहीं है</Text>
      }
      renderItem={({ item }) => (
        <TouchableOpacity
          style={styles.card}
          onPress={() => navigation.navigate('WorkDetail', { workId: item.work_id, workName: item.work_name })}
        >
          <View style={styles.row}>
            {item.thumbnail ? (
              <Image source={{ uri: item.thumbnail }} style={styles.thumb} />
            ) : (
              <LocationPinPlaceholder style={styles.thumb} size={64} />
            )}
            <View style={styles.info}>
              <Text style={styles.label}>
                कार्य का नाम : <Text style={styles.value}>{item.work_name}</Text>
              </Text>
              <Text style={styles.label}>
                कार्य का प्रकार : <Text style={styles.value}>{item.work_type_name}</Text>
              </Text>
              <Text style={styles.label}>
                कार्य प्रगति चरण : <Text style={styles.value}>{item.work_stage_name || '-'}</Text>
              </Text>
              <Text style={styles.label}>
                राशि: <Text style={styles.value}>₹{item.sanction_amount.toLocaleString('en-IN')}</Text>
              </Text>
            </View>
          </View>
          <View style={styles.footerRow}>
            <Text style={styles.dateText}>तिथि : {item.created_at}</Text>
            {item.due_date ? <Text style={styles.dateText}>अंतिम दिनांक : {item.due_date}</Text> : null}
          </View>
          <View style={styles.divider} />
          <Text style={styles.meta}>कार्य एजेंसी : {item.office_name}</Text>
          {item.area ? <Text style={styles.meta}>क्षेत्र : {item.area}</Text> : null}
        </TouchableOpacity>
      )}
    />
  );
}

const styles = StyleSheet.create({
  screen: { backgroundColor: colors.background },
  center: { flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: colors.background },
  empty: { textAlign: 'center', marginTop: 40, color: colors.muted },
  card: {
    backgroundColor: colors.white,
    borderRadius: 12,
    padding: 14,
    marginBottom: 14,
    elevation: 1,
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 3,
  },
  row: { flexDirection: 'row' },
  thumb: { width: 64, height: 64, borderRadius: 10, marginRight: 12, backgroundColor: colors.cardAlt },
  info: { flex: 1 },
  label: { fontSize: 13, color: colors.muted, marginBottom: 2 },
  value: { color: colors.primaryLight, fontWeight: '600' },
  footerRow: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 },
  dateText: { fontSize: 12, color: '#b8790b', fontWeight: '600' },
  divider: { height: 1, backgroundColor: colors.border, marginVertical: 8 },
  meta: { fontSize: 12, color: colors.text, marginBottom: 2 },
});
