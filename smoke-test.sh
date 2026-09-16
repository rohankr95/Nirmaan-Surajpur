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
  -d "village=1" -d "dp=1" -d "office=1" -d "employeeAdmin=1" \
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

UPLOADED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM technical_sanctions WHERE upload_file IS NOT NULL" 2>/dev/null)
check "both rapid uploads were stored" "$UPLOADED" "2"
DISTINCT=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(DISTINCT upload_file) FROM technical_sanctions WHERE upload_file IS NOT NULL" 2>/dev/null)
check "rapid uploads got distinct filenames" "$DISTINCT" "2"

# A file that claims to be an image but will not decode must not 500.
printf 'not really a png' > "$TMP/corrupt.png"
TOKEN=$(csrf "$TS_FORM")
CODE=$(post_code "$BASE/technical-sanction" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "ts_no=TS-C" \
  -F "submission_date=2026-05-01" -F "ts_amount=300000" -F "work_status=3" \
  -F "file=@$TMP/corrupt.png;type=image/png")
check "undecodable image does not crash the save" "$CODE" "302"

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
