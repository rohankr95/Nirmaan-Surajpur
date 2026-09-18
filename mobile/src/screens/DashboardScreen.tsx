import React, { useCallback, useState } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import {
  ActivityIndicator,
  RefreshControl,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import { api } from '../api/client';
import { DashboardResponse } from '../api/types';
import { useAuth } from '../context/AuthContext';
import { colors } from '../theme/colors';

const CARDS: { key: keyof DashboardResponse['counts']; label: string; color: string }[] = [
  { key: 'in_progress', label: 'कार्य प्रगति पर', color: '#1565c0' },
  { key: 'closed', label: 'कार्य बंद', color: '#c0392b' },
  { key: 'complete', label: 'कार्य पूर्ण', color: '#2e9e5b' },
  { key: 'rejected', label: 'कार्य निरस्त', color: '#8e44ad' },
];

export default function DashboardScreen() {
  const { user, logout } = useAuth();
  const [data, setData] = useState<DashboardResponse | null>(null);
  const [loading, setLoading] = useState(true);

  const load = useCallback(async () => {
    try {
      const { data } = await api.get<DashboardResponse>('/dashboard');
      setData(data);
    } finally {
      setLoading(false);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
      load();
    }, [load])
  );

  return (
    <View style={styles.screen}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>निर्माण जशपुर</Text>
        <TouchableOpacity onPress={logout}>
          <Text style={styles.logout}>⏻</Text>
        </TouchableOpacity>
      </View>
      <ScrollView
        contentContainerStyle={styles.body}
        refreshControl={<RefreshControl refreshing={loading} onRefresh={load} />}
      >
        <Text style={styles.hello}>
          Hello <Text style={styles.helloName}>{user?.name}</Text>
        </Text>
        <Text style={styles.department}>Department : {data?.user.office_name ?? user?.office_name}</Text>

        {loading && !data ? (
          <ActivityIndicator style={{ marginTop: 30 }} color={colors.primary} />
        ) : (
          <View style={styles.grid}>
            {CARDS.map((card) => (
              <View key={card.key} style={styles.card}>
                <Text style={[styles.cardCount, { color: card.color }]}>
                  {data?.counts[card.key] ?? 0}
                </Text>
                <Text style={styles.cardLabel}>{card.label}</Text>
              </View>
            ))}
          </View>
        )}
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  screen: { flex: 1, backgroundColor: colors.background },
  header: {
    backgroundColor: colors.primaryLight,
    paddingTop: 50,
    paddingBottom: 16,
    paddingHorizontal: 20,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  headerTitle: { color: colors.white, fontSize: 20, fontWeight: '700' },
  logout: { color: colors.white, fontSize: 22 },
  body: { padding: 20 },
  hello: { fontSize: 18, color: colors.text },
  helloName: { color: '#d99a00', fontWeight: '700' },
  department: { color: '#d99a00', fontWeight: '600', marginTop: 4, marginBottom: 20 },
  grid: { flexDirection: 'row', flexWrap: 'wrap', justifyContent: 'space-between' },
  card: {
    width: '48%',
    backgroundColor: colors.white,
    borderRadius: 12,
    paddingVertical: 20,
    alignItems: 'center',
    marginBottom: 14,
    elevation: 2,
    shadowColor: '#000',
    shadowOpacity: 0.06,
    shadowRadius: 4,
  },
  cardCount: { fontSize: 26, fontWeight: '700' },
  cardLabel: { color: colors.text, marginTop: 6, fontSize: 13 },
});
