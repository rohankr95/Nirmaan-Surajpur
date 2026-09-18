import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Platform } from 'react-native';

/**
 * Point this at the Laravel backend. 10.0.2.2 is the Android emulator's
 * alias for the host machine's localhost; a real phone on the same Wi-Fi
 * needs the host's LAN IP instead (e.g. http://192.168.1.20:8000), and a
 * deployed backend should use its real https:// URL.
 */
const DEV_HOST = Platform.OS === 'android' ? '10.0.2.2' : 'localhost';
export const API_BASE_URL = process.env.EXPO_PUBLIC_API_BASE_URL ?? `http://${DEV_HOST}:8000/api`;

const TOKEN_KEY = 'nirmaan_api_token';

export const api = axios.create({ baseURL: API_BASE_URL });

export async function setApiToken(token: string | null) {
  if (token) {
    await AsyncStorage.setItem(TOKEN_KEY, token);
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
  } else {
    await AsyncStorage.removeItem(TOKEN_KEY);
    delete api.defaults.headers.common.Authorization;
  }
}

export async function loadStoredToken(): Promise<string | null> {
  const token = await AsyncStorage.getItem(TOKEN_KEY);
  if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
  }
  return token;
}

export function apiErrorMessage(error: unknown, fallback = 'कुछ गड़बड़ी हुई, कृपया पुनः प्रयास करें'): string {
  if (axios.isAxiosError(error)) {
    const message = error.response?.data?.message;
    if (typeof message === 'string') return message;
  }
  return fallback;
}
