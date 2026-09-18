import React, { useState } from 'react';
import {
  ActivityIndicator,
  Image,
  KeyboardAvoidingView,
  Platform,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { useAuth } from '../context/AuthContext';
import { colors } from '../theme/colors';

export default function LoginScreen() {
  const { login } = useAuth();
  const [loginId, setLoginId] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit() {
    if (!loginId || !password) {
      setError('कृपया आईडी और पासवर्ड दर्ज करें');
      return;
    }
    setSubmitting(true);
    setError(null);
    try {
      await login(loginId.trim(), password);
    } catch (e: any) {
      setError(e.message);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <View style={styles.brandWrap}>
        <View style={styles.logoCircle}>
          <Text style={styles.logoText}>नि</Text>
        </View>
        <Text style={styles.brandTitle}>निर्माण सूरजपुर</Text>
        <Text style={styles.brandSubtitle}>कार्य प्रगति ऐप</Text>
      </View>

      <View style={styles.form}>
        <Text style={styles.label}>लॉगिन आईडी</Text>
        <TextInput
          style={styles.input}
          placeholder="आईडी दर्ज करें"
          autoCapitalize="none"
          value={loginId}
          onChangeText={setLoginId}
        />
        <Text style={styles.label}>पासवर्ड</Text>
        <TextInput
          style={styles.input}
          placeholder="******"
          secureTextEntry
          value={password}
          onChangeText={setPassword}
        />
        {error ? <Text style={styles.error}>{error}</Text> : null}
        <TouchableOpacity style={styles.button} onPress={handleSubmit} disabled={submitting}>
          {submitting ? (
            <ActivityIndicator color={colors.white} />
          ) : (
            <Text style={styles.buttonText}>लॉगिन करें</Text>
          )}
        </TouchableOpacity>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.primary, justifyContent: 'center', padding: 24 },
  brandWrap: { alignItems: 'center', marginBottom: 40 },
  logoCircle: {
    width: 72,
    height: 72,
    borderRadius: 36,
    backgroundColor: colors.white,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 12,
  },
  logoText: { fontSize: 28, fontWeight: '700', color: colors.primary },
  brandTitle: { fontSize: 24, fontWeight: '700', color: colors.white },
  brandSubtitle: { fontSize: 14, color: '#cbd8ea', marginTop: 4 },
  form: { backgroundColor: colors.white, borderRadius: 16, padding: 20 },
  label: { fontSize: 13, color: colors.muted, marginBottom: 6, marginTop: 12 },
  input: {
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: 10,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 16,
  },
  error: { color: colors.danger, marginTop: 12, textAlign: 'center' },
  button: {
    backgroundColor: colors.primary,
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 20,
  },
  buttonText: { color: colors.white, fontSize: 16, fontWeight: '600' },
});
