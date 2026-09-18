import React from 'react';
import { StyleSheet, Text, View, ViewStyle } from 'react-native';
import { colors } from '../theme/colors';

/**
 * Stand-in thumbnail for a work that has no progress photo yet -- a plain
 * View/Text drawing rather than a bundled image asset, so there is nothing
 * to keep in sync with a real design file.
 */
export default function LocationPinPlaceholder({ style, size = 64 }: { style?: ViewStyle; size?: number }) {
  return (
    <View style={[styles.box, { width: size, height: size, borderRadius: size * 0.18 }, style]}>
      <Text style={[styles.pin, { fontSize: size * 0.5 }]}>📍</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  box: {
    backgroundColor: colors.accent,
    alignItems: 'center',
    justifyContent: 'center',
  },
  pin: { color: colors.white },
});
