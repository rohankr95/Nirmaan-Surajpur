import React from 'react';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Text } from 'react-native';
import DashboardScreen from '../screens/DashboardScreen';
import WorkStackNavigator from './WorkStackNavigator';
import ProfileStackNavigator from './ProfileStackNavigator';
import { colors } from '../theme/colors';
import type { MainTabParamList } from './types';

const Tab = createBottomTabNavigator<MainTabParamList>();

const ICONS: Record<keyof MainTabParamList, string> = {
  Dashboard: '🏠',
  WorkProgress: '💼',
  Profile: '👤',
};

export default function MainTabs() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarActiveTintColor: colors.primaryLight,
        tabBarInactiveTintColor: colors.muted,
        tabBarIcon: () => <Text style={{ fontSize: 20 }}>{ICONS[route.name as keyof MainTabParamList]}</Text>,
      })}
    >
      <Tab.Screen name="Dashboard" component={DashboardScreen} options={{ title: 'डैशबोर्ड' }} />
      <Tab.Screen name="WorkProgress" component={WorkStackNavigator} options={{ title: 'कार्य प्रगति' }} />
      <Tab.Screen name="Profile" component={ProfileStackNavigator} options={{ title: 'प्रोफाइल' }} />
    </Tab.Navigator>
  );
}
