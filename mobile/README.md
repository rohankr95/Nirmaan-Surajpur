# निर्माण सूरजपुर — Mobile App

A React Native (Expo) app for field engineers/officers: dashboard, work list,
stage-wise progress updates with a geo-tagged photo, and profile. It talks to
the same Laravel database as the web app through a separate token-based JSON
API (`routes/api.php` in the Laravel project), so the web app's session login
is untouched.

## What's covered

- Login (Sanctum token)
- Dashboard: कार्य प्रगति पर / कार्य बंद / कार्य पूर्ण / कार्य निरस्त counts
- कार्य प्रगति tab: work list, scoped the same way the web app scopes by
  office/employee
- Work detail: सामान्य जानकारी + per-stage पूर्ण % cards (stage_number /
  work.stages), each with its latest estimated date/amount/remark/photo
- प्रोग्रेस अपडेट करे: submit a stage update with photo(s) (camera or
  gallery) and an optional device GPS fix (updates the work's map location,
  same `latitude`/`longitude` columns the web app's map view uses)
- Profile: view/edit, change password, logout

Everything not in this list (masters, sanctions, tenders, reports, payments,
dossiers) was deliberately left off the phone — see the root project's
conversation history for why: this app is scoped to what a field
engineer/officer actually needs on a phone, not a port of the whole back
office.

## Project layout

```
mobile/
  App.tsx                  # navigation root + auth gate
  src/
    api/          client.ts (axios + token storage), types.ts
    context/       AuthContext.tsx
    navigation/    tab + stack navigators
    screens/       one file per screen
    theme/         colors.ts
    components/    LocationPinPlaceholder.tsx (no-photo-yet thumbnail)
```

## Running it

```sh
cd mobile
npm install
EXPO_PUBLIC_API_BASE_URL="http://<your-backend-host>:8000/api" npx expo start
```

Point `EXPO_PUBLIC_API_BASE_URL` at wherever the Laravel app is reachable
from the device you're testing on:

- **Android emulator** on the same machine as `php artisan serve`: the
  default in `src/api/client.ts` already handles this (`10.0.2.2` is the
  emulator's alias for the host's `localhost`) — you don't need to set the
  env var.
- **A real phone** on the same Wi-Fi as your dev machine: use your
  machine's LAN IP, e.g. `http://192.168.1.20:8000/api`.
- **A deployed backend** (e.g. the Railway deployment): use its real
  `https://` URL.

Then either:

- Press `w` in the Expo CLI to open the web preview (useful for a quick
  sanity check on desktop — most UI works, though `Alert.alert` doesn't
  render anything on web, and camera/location prompts behave like a
  browser, not a phone), or
- Scan the QR code with the **Expo Go** app on an Android/iOS phone (fastest
  way to try the real thing), or
- `npx expo run:android` / `npx expo run:ios` for a dev build with native
  modules, if you have Android Studio / Xcode installed.

## Building a real, installable app

This was built and verified in a sandboxed container with no Android SDK
and no macOS/Xcode, so no `.apk`/`.ipa` was produced here — only verified
functionally via `npx expo start --web` driven end-to-end against the real
Laravel API (login, dashboard, work list/detail, stage-wise progress
submission with a photo and device GPS, profile, logout — all confirmed
against the actual database, not mocked).

To get an installable Android build, from a machine with internet access to
Google's Android SDK servers:

- **Easiest — no Android Studio needed:** [EAS Build](https://docs.expo.dev/build/introduction/)
  (Expo's free-tier cloud build service):
  ```sh
  npm install -g eas-cli
  eas login
  eas build:configure
  eas build --platform android --profile preview
  ```
  This produces a downloadable `.apk` without installing any Android
  tooling locally.
- **Local build:** install Android Studio (which includes the Android SDK),
  then `npx expo run:android` from this directory.

iOS needs a Mac either way (Xcode, or `eas build --platform ios` from a Mac
with an Apple Developer account for signing).

## Backend API

See `routes/api.php`, `app/Http/Controllers/Api/*` in the Laravel project.
Token auth via Sanctum (`POST /api/login` → bearer token, `Authorization:
Bearer <token>` on everything else). Covered by `smoke-test.sh`'s mobile API
section.
