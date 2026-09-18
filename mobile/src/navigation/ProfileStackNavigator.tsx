import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import ProfileScreen from '../screens/ProfileScreen';
import ChangePasswordScreen from '../screens/ChangePasswordScreen';
import { colors } from '../theme/colors';
import type { ProfileStackParamList } from './types';

const Stack = createNativeStackNavigator<ProfileStackParamList>();

export default function ProfileStackNavigator() {
  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.primaryLight },
        headerTintColor: colors.white,
        headerTitleStyle: { fontWeight: '700' },
      }}
    >
      <Stack.Screen name="ProfileHome" component={ProfileScreen} options={{ title: 'प्रोफाइल' }} />
      <Stack.Screen name="ChangePassword" component={ChangePasswordScreen} options={{ title: 'पासवर्ड बदलें' }} />
    </Stack.Navigator>
  );
}
