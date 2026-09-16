#!/usr/bin/env bash
# End-to-end smoke test against a running dev server.
#
#   php artisan migrate:fresh --seed
#   php artisan serve
#   ./smoke-test.sh
#
# Exercises the flows that touch the database schema, so column mismatches
# surface here rather than in front of a user.

BASE="${BASE:-http://127.0.0.1:8000}"
JAR=$(mktemp)
TMP=$(mktemp -d)
PASS=0; FAIL=0

red()   { printf '\033[31m%s\033[0m\n' "$1"; }
green() { printf '\033[32m%s\033[0m\n' "$1"; }

ok()   { green "  PASS  $1"; PASS=$((PASS+1)); }
bad()  { red   "  FAIL  $1"; FAIL=$((FAIL+1)); }

check() { # check <description> <actual> <expected>
  if [ "$2" = "$3" ]; then ok "$1 ($2)"; else bad "$1 — got $2, want $3"; fi
}

csrf() { # csrf <url>  -> prints token
  # Forms carry the token as a hidden input; the AJAX screens inline it into
  # JS as _token: '...'. Same session token either way, so accept both.
  curl -s -b "$JAR" -c "$JAR" "$1" > "$TMP/page.html"
  local t
  t=$(grep -o 'name="_token" value="[^"]*"' "$TMP/page.html" | head -1 | sed -E 's/.*value="([^"]*)"/\1/')
  [ -z "$t" ] && t=$(grep -oE "_token: *'[^']*'" "$TMP/page.html" | head -1 | sed -E "s/.*'([^']*)'/\1/")
  [ -z "$t" ] && t=$(grep -o 'name="csrf-token" content="[^"]*"' "$TMP/page.html" | head -1 | sed -E 's/.*content="([^"]*)"/\1/')
  printf '%s' "$t"
}

post_code() { # post_code <url> <curl args...>  -> prints http status
  local url="$1"; shift
  curl -s -b "$JAR" -c "$JAR" -o "$TMP/out.html" -w '%{http_code}' -X POST "$url" "$@"
}

echo "=== Nirmaan smoke test — $BASE ==="

# --- login ---------------------------------------------------------------
TOKEN=$(csrf "$BASE/login")
CODE=$(post_code "$BASE/authenticate" -d "_token=$TOKEN" -d "login_id=admin" -d "password=admin123")
check "login as admin" "$CODE" "302"

CODE=$(curl -s -b "$JAR" -o "$TMP/dash.html" -w '%{http_code}' "$BASE/dashboard")
check "dashboard loads" "$CODE" "200"

# --- create a work -------------------------------------------------------
TOKEN=$(csrf "$BASE/work/create")
CODE=$(post_code "$BASE/work" \
  -d "_token=$TOKEN" \
  -d "work_name=स्मोक टेस्ट सीसी रोड" \
  -d "fy=2" -d "scheme=1" -d "work_type=1" -d "location_type=1" \
  -d "village=1" -d "dp=1" -d "office=1" -d "employeeAdmin=1" -d "sdo_emp_id=2" \
  -d "unit_work=1" \
  -d "dpr_startDate=2026-04-01" -d "dpr_endDate=2026-04-30" \
  -d "workComplete_endDate=2027-03-31")
check "create work" "$CODE" "302"

WORK_ID=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT work_id FROM works ORDER BY work_id DESC LIMIT 1" 2>/dev/null)
if [ -n "$WORK_ID" ]; then ok "work persisted (id=$WORK_ID)"; else bad "work was not written to the database"; fi

CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/work")
check "work list loads" "$CODE" "200"

# --- a real upload, to catch column mismatches ---------------------------
# Must be a genuinely decodable image: the upload path runs it through GD.
php -r '$im=imagecreatetruecolor(120,90);imagefill($im,0,0,imagecolorallocate($im,30,90,140));imagepng($im,$argv[1]);' "$TMP/photo.png"

TOKEN=$(csrf "$BASE/work-progress/create?work_id=$WORK_ID")
CODE=$(post_code "$BASE/work-progress" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "mb_stages=1" -F "work_status=9" \
  -F "expenditure_amount=50000" -F "description=स्मोक टेस्ट प्रगति" \
  -F "estimated_completion_date=2027-01-31" \
  -F "file=@$TMP/photo.png;type=image/png")
check "add work progress with photo" "$CODE" "302"

WP=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_progress WHERE work_id=$WORK_ID AND upload_file IS NOT NULL" 2>/dev/null)
check "progress photo stored in upload_file" "$WP" "1"

# --- work completion, previously impossible on a fresh DB ----------------
TOKEN=$(csrf "$BASE/work")
CODE=$(post_code "$BASE/work-complete" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" \
  -F "work_completion_date=2026-09-15" -F "remark=स्मोक टेस्ट पूर्ण" \
  -F "file=@$TMP/photo.png;type=image/png")
check "mark work complete with document" "$CODE" "302"

WC=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_completes WHERE work_id=$WORK_ID AND upload_file IS NOT NULL" 2>/dev/null)
check "completion document stored" "$WC" "1"

STATUS=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT work_status FROM works WHERE work_id=$WORK_ID" 2>/dev/null)
check "work moved to complete status" "$STATUS" "10"

# --- reports still render ------------------------------------------------
for path in "reports/works" "reports/block-wise" "reports/scheme-wise" "reports/logs-list"; do
  CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/$path")
  check "GET /$path" "$CODE" "200"
done

CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/reports/work-details/$WORK_ID")
check "work detail page" "$CODE" "200"

# --- two uploads in the same second must not overwrite each other --------
# The TS entry form lives on the edit route; create() renders a stub with no form.
TS_FORM="$BASE/technical-sanction/$WORK_ID/edit"

# Counts are taken relative to a baseline so the script can be re-run against
# an already-populated database without the assertions drifting.
TS_BEFORE=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM technical_sanctions WHERE upload_file IS NOT NULL" 2>/dev/null)

TOKEN=$(csrf "$TS_FORM")
post_code "$BASE/technical-sanction" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "ts_no=TS-A" \
  -F "submission_date=2026-05-01" -F "ts_amount=100000" -F "work_status=3" \
  -F "file=@$TMP/photo.png;type=image/png" > /dev/null
TOKEN=$(csrf "$TS_FORM")
post_code "$BASE/technical-sanction" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "ts_no=TS-B" \
  -F "submission_date=2026-05-01" -F "ts_amount=200000" -F "work_status=3" \
  -F "file=@$TMP/photo.png;type=image/png" > /dev/null

TS_AFTER=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM technical_sanctions WHERE upload_file IS NOT NULL" 2>/dev/null)
check "both rapid uploads were stored" "$TS_AFTER" "$((TS_BEFORE + 2))"
# Every stored upload must still have its own filename.
DISTINCT=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(DISTINCT upload_file) FROM technical_sanctions WHERE upload_file IS NOT NULL" 2>/dev/null)
check "rapid uploads got distinct filenames" "$DISTINCT" "$TS_AFTER"

# A file that claims to be an image but is not one must be rejected cleanly —
# not saved, and not a 500. Work progress accepted anything at all until the
# mimes rule was added, which is how broken images reached the gallery.
printf 'not really a png' > "$TMP/corrupt.png"
BEFORE=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM work_progress" 2>/dev/null)
TOKEN=$(csrf "$BASE/work-progress/create?work_id=$WORK_ID")
CODE=$(post_code "$BASE/work-progress" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "mb_stages=2" -F "work_status=9" \
  -F "description=corrupt upload" \
  -F "file=@$TMP/corrupt.png;type=image/png")
check "non-image upload does not crash the request" "$CODE" "302"
AFTER=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM work_progress" 2>/dev/null)
check "non-image upload is rejected, not stored" "$AFTER" "$BEFORE"

# A genuine PDF is a legitimate progress attachment and must be accepted.
printf '%%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%%%EOF\n' > "$TMP/doc.pdf"
TOKEN=$(csrf "$BASE/work-progress/create?work_id=$WORK_ID")
CODE=$(post_code "$BASE/work-progress" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "mb_stages=2" -F "work_status=9" \
  -F "description=pdf attachment" \
  -F "file=@$TMP/doc.pdf;type=application/pdf")
check "PDF attachment accepted" "$CODE" "302"
WITHPDF=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_progress WHERE upload_file LIKE '%.pdf'" 2>/dev/null)
if [ "$WITHPDF" -ge 1 ]; then ok "PDF stored on the progress entry"; else bad "PDF was not stored"; fi

# --- village master CRUD -------------------------------------------------
CODE=$(curl -s -b "$JAR" -o "$TMP/vlist.html" -w '%{http_code}' "$BASE/master/village")
check "village master lists" "$CODE" "200"

TOKEN=$(csrf "$BASE/master/village")
CODE=$(post_code "$BASE/master/village" \
  -d "_token=$TOKEN" -d "village_name=स्मोक ग्राम" -d "village_name_en=Smoke Village" \
  -d "grampanchayat_id=1")
check "create village" "$CODE" "302"
VID=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT village_id FROM villages WHERE village_name_en='Smoke Village'" 2>/dev/null)
if [ -n "$VID" ]; then ok "village persisted (id=$VID)"; else bad "village not written"; fi

# Route-model binding here depends on Village::$primaryKey being exactly
# "village_id"; it used to carry a trailing space, which broke every lookup.
CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/master/village/$VID/edit")
check "village edit form loads" "$CODE" "200"

TOKEN=$(csrf "$BASE/master/village")
CODE=$(post_code "$BASE/master/village/$VID" \
  -d "_token=$TOKEN" -d "_method=PUT" -d "village_name=स्मोक ग्राम संशोधित" \
  -d "village_name_en=Smoke Village Edited" -d "grampanchayat_id=2")
check "update village" "$CODE" "302"
RENAMED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM villages WHERE village_id=$VID AND village_name_en='Smoke Village Edited' AND grampanchayat_id=2" 2>/dev/null)
check "village update persisted" "$RENAMED" "1"

# A village attached to a work must not be deletable.
TOKEN=$(csrf "$BASE/master/village")
post_code "$BASE/master/village/1" -d "_token=$TOKEN" -d "_method=DELETE" > /dev/null
STILL=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM villages WHERE village_id=1" 2>/dev/null)
check "village in use is protected from deletion" "$STILL" "1"

TOKEN=$(csrf "$BASE/master/village")
post_code "$BASE/master/village/$VID" -d "_token=$TOKEN" -d "_method=DELETE" > /dev/null
GONE=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM villages WHERE village_id=$VID" 2>/dev/null)
check "unused village deletes" "$GONE" "0"

# --- engineer / SDO assignment and contacts ------------------------------
SDO=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT sdo_emp_id FROM works WHERE work_id=$WORK_ID" 2>/dev/null)
check "SDO recorded on the work" "$SDO" "2"

curl -s -b "$JAR" -o "$TMP/detail.html" "$BASE/reports/work-details/$WORK_ID"
if grep -q "उत्तरदायी अधिकारी" "$TMP/detail.html"; then ok "responsible-officer panel renders"
else bad "responsible-officer panel missing"; fi
# emp_mobile has always been stored; it was never shown anywhere.
if grep -q "9876543210" "$TMP/detail.html"; then ok "sub-engineer mobile shown"
else bad "sub-engineer mobile not shown on work detail"; fi
if grep -q "9876500011" "$TMP/detail.html"; then ok "SDO mobile shown"
else bad "SDO mobile not shown on work detail"; fi

# The admin create form must list employees; the options used to be wrapped in
# an isset($work) guard, leaving the dropdown empty on create.
curl -s -b "$JAR" -o "$TMP/wform.html" "$BASE/work/create"
if grep -q "रमेश कुमार" "$TMP/wform.html"; then ok "admin create form lists employees"
else bad "admin create form has an empty employee dropdown"; fi

# --- stage-grouped photo gallery -----------------------------------------
curl -s -b "$JAR" -o "$TMP/detail.html" "$BASE/reports/work-details/$WORK_ID"
if grep -q "कार्य के छायाचित्र" "$TMP/detail.html"; then ok "photo gallery panel renders"
else bad "photo gallery panel missing"; fi
# The progress photo was uploaded against stage 1 (नींव कार्य), so the gallery
# must group it under that stage rather than dumping it in a flat list.
if grep -q "नींव कार्य" "$TMP/detail.html"; then ok "photos grouped under their stage"
else bad "gallery did not group photos by stage"; fi
if grep -qE 'images/Work-Progress/[a-f0-9]+\.png' "$TMP/detail.html"; then ok "progress photo rendered in gallery"
else bad "progress photo not rendered"; fi
if grep -qE 'images/Work-Complete/[a-f0-9]+\.png' "$TMP/detail.html"; then ok "completion photo rendered in gallery"
else bad "completion photo not rendered"; fi

# --- per-work audit trail ------------------------------------------------
TRAIL=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM log_activities WHERE work_id=$WORK_ID" 2>/dev/null)
if [ "$TRAIL" -ge 4 ]; then ok "work has an audit trail ($TRAIL entries)"
else bad "expected several audit entries for work $WORK_ID, got $TRAIL"; fi

ORPHAN=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM log_activities WHERE subject_type='Work' AND subject_id IS NULL" 2>/dev/null)
check "work entries carry a subject_id" "$ORPHAN" "0"

# The trail must cover actions taken on the work's related records, not just
# the work row itself.
KINDS=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(DISTINCT subject_type) FROM log_activities WHERE work_id=$WORK_ID" 2>/dev/null)
if [ "$KINDS" -ge 3 ]; then ok "trail spans $KINDS record types"
else bad "expected the trail to span several record types, got $KINDS"; fi

curl -s -b "$JAR" -o "$TMP/detail.html" "$BASE/reports/work-details/$WORK_ID"
if grep -q "पूर्ववृत्त जानकारी" "$TMP/detail.html"; then ok "history panel renders on work detail"
else bad "history panel missing from work detail"; fi
if grep -q "नया कार्य जोड़ा गया" "$TMP/detail.html"; then ok "trail entries render in Hindi"
else bad "expected a Hindi trail entry on the detail page"; fi

# --- employee provisioning must not reuse a shared password --------------
EMAIL="smoke$(date +%s)@nirmaan.test"
TOKEN=$(csrf "$BASE/master/employee")
CODE=$(post_code "$BASE/master/employee" \
  -d "_token=$TOKEN" -d "name=स्मोक कर्मचारी" -d "mobile=9000000099" \
  -d "email=$EMAIL" -d "designation_id=1" -d "office_id=1")
check "create employee" "$CODE" "302"

HASHES=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(DISTINCT password) FROM users WHERE user_role_id=3" 2>/dev/null)
NEWUSERS=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM users WHERE login_id='$EMAIL' AND force_password_reset=1" 2>/dev/null)
check "provisioned login is flagged for reset" "$NEWUSERS" "1"

# Two employee logins must never share a password hash.
EMAIL2="smoke$(date +%s)b@nirmaan.test"
TOKEN=$(csrf "$BASE/master/employee")
post_code "$BASE/master/employee" \
  -d "_token=$TOKEN" -d "name=स्मोक कर्मचारी दो" -d "mobile=9000000098" \
  -d "email=$EMAIL2" -d "designation_id=1" -d "office_id=1" > /dev/null
SHARED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) - COUNT(DISTINCT password) FROM users WHERE login_id IN ('$EMAIL','$EMAIL2')" 2>/dev/null)
check "provisioned logins have distinct passwords" "$SHARED" "0"

rm -rf "$JAR" "$TMP"
echo
echo "=== $PASS passed, $FAIL failed ==="
[ "$FAIL" -eq 0 ]
