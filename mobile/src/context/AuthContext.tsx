import React, { createContext, useContext, useEffect, useState } from 'react';
import { api, apiErrorMessage, loadStoredToken, setApiToken } from '../api/client';
import { ApiUser } from '../api/types';

interface AuthContextValue {
  user: ApiUser | null;
  loading: boolean;
  login: (loginId: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  refreshUser: () => Promise<void>;
  setUser: (user: ApiUser) => void;
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<ApiUser | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      const token = await loadStoredToken();
      if (token) {
        try {
          const { data } = await api.get('/me');
          setUser(data.user);
        } catch {
          await setApiToken(null);
        }
      }
      setLoading(false);
    })();
  }, []);

  async function login(loginId: string, password: string) {
    try {
      const { data } = await api.post('/login', { login_id: loginId, password });
      await setApiToken(data.token);
      setUser(data.user);
    } catch (error) {
      throw new Error(apiErrorMessage(error, 'लॉगिन आईडी या पासवर्ड गलत है'));
    }
  }

  async function logout() {
    try {
      await api.post('/logout');
    } catch {
      // token may already be invalid server-side; clear locally regardless
    }
    await setApiToken(null);
    setUser(null);
  }

  async function refreshUser() {
    const { data } = await api.get('/me');
    setUser(data.user);
  }

  return (
    <AuthContext.Provider value={{ user, loading, login, logout, refreshUser, setUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
}
