import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import WorkListScreen from '../screens/WorkListScreen';
import WorkDetailScreen from '../screens/WorkDetailScreen';
import ProgressUpdateScreen from '../screens/ProgressUpdateScreen';
import { colors } from '../theme/colors';
import type { WorkStackParamList } from './types';

const Stack = createNativeStackNavigator<WorkStackParamList>();

export default function WorkStackNavigator() {
  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.primaryLight },
        headerTintColor: colors.white,
        headerTitleStyle: { fontWeight: '700' },
      }}
    >
      <Stack.Screen name="WorkList" component={WorkListScreen} options={{ title: 'कार्य प्रगति' }} />
      <Stack.Screen name="WorkDetail" component={WorkDetailScreen} options={{ title: 'कार्य विवरण' }} />
      <Stack.Screen
        name="ProgressUpdate"
        component={ProgressUpdateScreen}
        options={{ title: 'प्रोग्रेस अपडेट करे' }}
      />
    </Stack.Navigator>
  );
}
