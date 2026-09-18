import React, { useState } from 'react';
import { ActivityIndicator, Alert, StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';
import { api, apiErrorMessage } from '../api/client';
import { useAuth } from '../context/AuthContext';
import { colors } from '../theme/colors';

export default function ChangePasswordScreen({ navigation }: any) {
  const { refreshUser } = useAuth();
  const [oldPassword, setOldPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [saving, setSaving] = useState(false);

  async function handleSubmit() {
    setSaving(true);
    try {
      await api.post('/change-password', {
        old_password: oldPassword,
        new_password: newPassword,
        confirm_password: confirmPassword,
      });
      // Clears force_password_reset on the user object too, so the forced
      // first-login flow hands off to the normal tab navigator on its own.
      await refreshUser();
      Alert.alert('सफल', 'पासवर्ड बदल दिया गया है', [{ text: 'ठीक है', onPress: () => navigation.goBack() }]);
    } catch (error) {
      Alert.alert('त्रुटि', apiErrorMessage(error));
    } finally {
      setSaving(false);
    }
  }

  return (
    <View style={styles.screen}>
      <Text style={styles.label}>पुराना पासवर्ड</Text>
      <TextInput style={styles.input} secureTextEntry value={oldPassword} onChangeText={setOldPassword} />
      <Text style={styles.label}>नया पासवर्ड</Text>
      <TextInput style={styles.input} secureTextEntry value={newPassword} onChangeText={setNewPassword} />
      <Text style={styles.label}>नया पासवर्ड दोबारा दर्ज करें</Text>
      <TextInput style={styles.input} secureTextEntry value={confirmPassword} onChangeText={setConfirmPassword} />
      <TouchableOpacity style={styles.button} onPress={handleSubmit} disabled={saving}>
        {saving ? <ActivityIndicator color={colors.white} /> : <Text style={styles.buttonText}>पासवर्ड बदलें</Text>}
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  screen: { flex: 1, backgroundColor: colors.background, padding: 16 },
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
  button: {
    backgroundColor: colors.primary,
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 24,
  },
  buttonText: { color: colors.white, fontWeight: '700' },
});
