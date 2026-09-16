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
  -d "unit_work=1" -d "sanction_amount=1000000" \
  -d "latitude=23.2156789" -d "longitude=82.8712345" \
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
  -F "files[]=@$TMP/photo.png;type=image/png" -F "files[]=@$TMP/photo.png;type=image/png")
check "add work progress with multiple photos" "$CODE" "302"

WP_ID=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT wp_id FROM work_progress WHERE work_id=$WORK_ID ORDER BY wp_id DESC LIMIT 1" 2>/dev/null)
WP=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_progress_images WHERE work_progress_id=$WP_ID" 2>/dev/null)
check "both progress photos stored" "$WP" "2"

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
  -F "files[]=@$TMP/corrupt.png;type=image/png")
check "non-image upload does not crash the request" "$CODE" "302"
AFTER=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM work_progress" 2>/dev/null)
check "non-image upload is rejected, not stored" "$AFTER" "$BEFORE"

# A genuine PDF is a legitimate progress attachment and must be accepted.
printf '%%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%%%EOF\n' > "$TMP/doc.pdf"
TOKEN=$(csrf "$BASE/work-progress/create?work_id=$WORK_ID")
CODE=$(post_code "$BASE/work-progress" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "mb_stages=2" -F "work_status=9" \
  -F "description=pdf attachment" \
  -F "files[]=@$TMP/doc.pdf;type=application/pdf")
check "PDF attachment accepted" "$CODE" "302"
WITHPDF=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_progress_images WHERE file_path LIKE '%.pdf'" 2>/dev/null)
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

# --- work category master and report -------------------------------------
CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/master/work_category")
check "work category master lists" "$CODE" "200"

CATNAME="स्मोक श्रेणी $RANDOM"
TOKEN=$(csrf "$BASE/master/work_category")
CODE=$(post_code "$BASE/master/work_category" -d "_token=$TOKEN" -d "work_category_name=$CATNAME")
check "create work category" "$CODE" "302"
CATID=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT work_category_id FROM work_categories ORDER BY work_category_id DESC LIMIT 1" 2>/dev/null)
if [ -n "$CATID" ]; then ok "category persisted (id=$CATID)"; else bad "category not written"; fi

CODE=$(curl -s -b "$JAR" -o "$TMP/cat.html" -w '%{http_code}' "$BASE/reports/category-wise")
check "category-wise report loads" "$CODE" "200"
# The seeded work belongs to a work type under "सड़क एवं पुल", so that row must
# report it rather than showing every category as empty.
if grep -q "सड़क एवं पुल" "$TMP/cat.html"; then ok "seeded category appears in the report"
else bad "seeded category missing from the report"; fi

# A category still attached to work types must not be deletable.
TOKEN=$(csrf "$BASE/master/work_category")
post_code "$BASE/master/work_category/1" -d "_token=$TOKEN" -d "_method=DELETE" > /dev/null
STILL=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_categories WHERE work_category_id=1" 2>/dev/null)
check "category in use is protected from deletion" "$STILL" "1"

# --- certificates and supporting documents -------------------------------
curl -s -b "$JAR" -o "$TMP/docs.html" "$BASE/reports/work-details/$WORK_ID"
if grep -q "प्रमाण पत्र एवं दस्तावेज़" "$TMP/docs.html"; then ok "documents panel renders"
else bad "documents panel missing"; fi

upload_doc() { # upload_doc <type> <file>
  local t; t=$(csrf "$BASE/reports/work-details/$WORK_ID")
  post_code "$BASE/work-documents" -F "_token=$t" -F "work_id=$WORK_ID" \
    -F "doc_type=$1" -F "reference_no=REF-$1" -F "document_date=2026-09-16" \
    -F "file=@$2"
}

CODE=$(upload_doc cc "$TMP/photo.png")
check "upload a completion certificate" "$CODE" "302"
CODE=$(upload_doc uc "$TMP/doc.pdf")
check "upload a utilisation certificate as PDF" "$CODE" "302"

DOCS=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_documents WHERE work_id=$WORK_ID AND doc_type IN ('cc','uc')" 2>/dev/null)
check "both certificates stored" "$DOCS" "2"

curl -s -b "$JAR" -o "$TMP/docs2.html" "$BASE/reports/work-details/$WORK_ID"
if grep -q "REF-cc" "$TMP/docs2.html"; then ok "certificate reference shown on the work"
else bad "certificate reference not shown"; fi

# An unsupported file type must be refused rather than stored.
printf 'binary junk' > "$TMP/bad.exe"
CODE=$(upload_doc other "$TMP/bad.exe")
check "unsupported document type is rejected" "$CODE" "302"
BAD=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_documents WHERE file_path LIKE '%.exe'" 2>/dev/null)
check "rejected document not stored" "$BAD" "0"

# The dashboard counts certificates outstanding on completed works only.
curl -s -b "$JAR" -o "$TMP/dash4.html" "$BASE/dashboard"
if grep -q "सीसी अपलोड लंबित" "$TMP/dash4.html"; then ok "certificate pending counters on the dashboard"
else bad "certificate pending counters missing"; fi

# --- geo-tagging and maps ------------------------------------------------
COORDS=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT CONCAT(latitude,',',longitude) FROM works WHERE work_id=$WORK_ID" 2>/dev/null)
check "coordinates stored on the work" "$COORDS" "23.2156789,82.8712345"

CODE=$(curl -s -b "$JAR" -o "$TMP/map.html" -w '%{http_code}' "$BASE/reports/work-map")
check "work map page loads" "$CODE" "200"
if grep -q "23.2156789" "$TMP/map.html"; then ok "geo-tagged work passed to the map"
else bad "work missing from the map payload"; fi
# Leaflet is vendored locally so the map works without internet access.
if grep -q "assets/leaflet/leaflet.js" "$TMP/map.html"; then ok "map uses the locally vendored Leaflet"
else bad "map is not loading local Leaflet"; fi
if [ -f public/assets/leaflet/leaflet.js ]; then ok "Leaflet asset present in public/"
else bad "Leaflet asset missing from public/"; fi

curl -s -b "$JAR" -o "$TMP/detail3.html" "$BASE/reports/work-details/$WORK_ID"
if grep -q "work-location-map" "$TMP/detail3.html"; then ok "location map on the work detail"
else bad "location map missing from work detail"; fi

curl -s -b "$JAR" -o "$TMP/dash3.html" "$BASE/dashboard"
if grep -q "जियो टैग लंबित" "$TMP/dash3.html"; then ok "geo-tag pending counter on the dashboard"
else bad "geo-tag pending counter missing"; fi

# The pending counters must react to real data, not sit at a fixed 0. A work
# with no lat/long and no RWH document must show up in both counts.
if grep -qE 'reports/pending-works\?type=geo' "$TMP/dash3.html"; then ok "geo card links to the pending-works list"
else bad "geo card is not a link to the pending list"; fi
if grep -qE 'reports/pending-works\?type=rwh' "$TMP/dash3.html"; then ok "RWH card links to the pending-works list"
else bad "RWH card is not a link to the pending list"; fi

RWH_BEFORE=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM works WHERE work_id=$WORK_ID AND work_status NOT IN (11,12)
   AND NOT EXISTS (SELECT 1 FROM work_documents d WHERE d.work_id=works.work_id AND d.doc_type='rwh')" 2>/dev/null)
check "work counted as RWH-pending before any RWH upload" "$RWH_BEFORE" "1"

# $WORK_ID was created with coordinates, so it can never test the geo-pending
# list. A second, deliberately untagged work is needed for that check.
UNTAGGED_NAME="स्मोक अनटैग्ड कार्य $RANDOM"
TOKEN=$(csrf "$BASE/work/create")
post_code "$BASE/work" -d "_token=$TOKEN" -d "work_name=$UNTAGGED_NAME" \
  -d "fy=2" -d "scheme=1" -d "work_type=1" -d "location_type=1" \
  -d "village=1" -d "dp=1" -d "office=1" -d "employeeAdmin=1" -d "unit_work=1" > /dev/null

CODE=$(curl -s -b "$JAR" -o "$TMP/pendgeo.html" -w '%{http_code}' "$BASE/reports/pending-works?type=geo")
check "geo pending-works list loads" "$CODE" "200"
if grep -q "$UNTAGGED_NAME" "$TMP/pendgeo.html"; then ok "geo-untagged work appears in its pending list"
else bad "geo-untagged work missing from pending list"; fi

CODE=$(curl -s -b "$JAR" -o "$TMP/pendrwh.html" -w '%{http_code}' "$BASE/reports/pending-works?type=rwh")
check "rwh pending-works list loads" "$CODE" "200"
if grep -q "स्मोक टेस्ट सीसी रोड" "$TMP/pendrwh.html"; then ok "RWH-pending work appears in its pending list"
else bad "RWH-pending work missing from pending list"; fi

# uc/cc were already uploaded to this work earlier in the run, so it must NOT
# appear in the uc/cc pending lists — pending must reflect actual doc state.
curl -s -b "$JAR" -o "$TMP/penduc.html" "$BASE/reports/pending-works?type=uc"
if grep -q "स्मोक टेस्ट सीसी रोड" "$TMP/penduc.html"; then bad "work with a UC on file still listed as UC-pending"
else ok "work with a UC on file is not listed as UC-pending"; fi

# --- office 1 must not be silently excluded from agency reports ----------
# Every agency-report method filtered out office_id=1 outright, and the admin
# work-create dropdown excluded it too, so the first office ever created
# (id=1 by auto-increment) was invisible in every agency-wise report and
# could never be picked from the work form — total_works stuck at "-".
FIRST_OFFICE=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT MIN(office_id) FROM offices" 2>/dev/null)

curl -s -b "$JAR" -o "$TMP/wcreate.html" "$BASE/work/create"
if grep -q "value=\"$FIRST_OFFICE\"" "$TMP/wcreate.html"; then ok "office 1 selectable on the work-create form"
else bad "office 1 missing from the work-create office dropdown"; fi

OFFICE1_WORKS=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM works WHERE office_id=$FIRST_OFFICE" 2>/dev/null)
curl -s -b "$JAR" -o "$TMP/agencywise.html" "$BASE/reports/agency-wise"
CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/reports/agency-wise")
check "agency-wise report loads" "$CODE" "200"
if [ "$OFFICE1_WORKS" -gt 0 ]; then
  if grep -qE ">$OFFICE1_WORKS<" "$TMP/agencywise.html" || grep -q ">$OFFICE1_WORKS</a>" "$TMP/agencywise.html"; then
    ok "office 1's real work count is shown, not hidden as zero"
  else
    bad "office 1's work count not found in the agency-wise report"
  fi
fi

# The same fix must hold for the other agency-scoped report endpoints.
for path in "reports/agency-wise-30-days-pending" "reports/employee-agency-wise" "reports/counts-uploaded-docs" "reports/last-status"; do
  CODE=$(curl -s -b "$JAR" -o "$TMP/rep.html" -w '%{http_code}' "$BASE/$path")
  check "GET /$path" "$CODE" "200"
done

# --- contractor master and work order ------------------------------------
CODE=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' "$BASE/master/contractor")
check "contractor master lists" "$CODE" "200"

CNAME="स्मोक ठेकेदार $RANDOM"
TOKEN=$(csrf "$BASE/master/contractor")
CODE=$(post_code "$BASE/master/contractor" \
  -d "_token=$TOKEN" -d "contractor_name=$CNAME" -d "contact_person=रा. सिंह" \
  -d "mobile=9812345670" -d "registration_no=REG-77")
check "create contractor" "$CODE" "302"
CID=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT contractor_id FROM contractors ORDER BY contractor_id DESC LIMIT 1" 2>/dev/null)
if [ -n "$CID" ]; then ok "contractor persisted (id=$CID)"; else bad "contractor not written"; fi

# The agreement row is the work order: number, date, amount, contractor, file.
TOKEN=$(csrf "$BASE/work")
CODE=$(post_code "$BASE/work-agreement" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "work_status=7" \
  -F "agreement_date=2026-07-05" -F "work_order_no=WO-$RANDOM" \
  -F "work_order_date=2026-07-01" -F "work_order_amount=950000" \
  -F "contractor_id=$CID" -F "remark=कार्य आदेश जारी" \
  -F "file=@$TMP/photo.png;type=image/png")
check "record a work order" "$CODE" "302"

WO=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM agreements WHERE work_id=$WORK_ID AND contractor_id=$CID AND work_order_amount=950000.00 AND upload_file IS NOT NULL" 2>/dev/null)
check "work order stored with contractor and document" "$WO" "1"

# A contractor holding work orders must not be deletable.
TOKEN=$(csrf "$BASE/master/contractor")
post_code "$BASE/master/contractor/$CID" -d "_token=$TOKEN" -d "_method=DELETE" > /dev/null
STILL=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM contractors WHERE contractor_id=$CID" 2>/dev/null)
check "contractor in use is protected from deletion" "$STILL" "1"

curl -s -b "$JAR" -o "$TMP/dos2.html" "$BASE/reports/work-dossier/$WORK_ID"
if grep -q "$CNAME" "$TMP/dos2.html"; then ok "contractor named on the dossier"
else bad "contractor missing from the dossier"; fi

# --- payment ledger ------------------------------------------------------
SANCTION=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT sanction_amount FROM works WHERE work_id=$WORK_ID" 2>/dev/null)
check "sanction amount recorded on the work" "$SANCTION" "1000000.00"

CODE=$(curl -s -b "$JAR" -o "$TMP/pay.html" -w '%{http_code}' "$BASE/payments")
check "payment list loads" "$CODE" "200"

add_payment() { # add_payment <type> <amount> <date> <remark>
  local t; t=$(csrf "$BASE/payments")
  post_code "$BASE/payments" -d "_token=$t" -d "work_id=$WORK_ID" \
    -d "payment_type=$1" -d "amount=$2" -d "payment_date=$3" -d "remark=$4"
}

CODE=$(add_payment released 400000 2026-06-12 "प्रथम भुगतान जारी किया गया")
check "record a released instalment" "$CODE" "302"
CODE=$(add_payment released 200000 2026-09-15 "द्वितीय किस्त")
check "record a second released instalment" "$CODE" "302"
CODE=$(add_payment expenditure 150000 2026-09-15 "विभाग द्वारा व्यय")
check "record an expenditure" "$CODE" "302"
CODE=$(add_payment evaluation 180000 2026-09-15 "इंजीनियर मूल्यांकन")
check "record an evaluation" "$CODE" "302"

LEDGER=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_payments WHERE work_id=$WORK_ID" 2>/dev/null)
check "ledger holds every entry" "$LEDGER" "4"

# Totals must be derived from the ledger, so released is the sum of both
# instalments rather than the latest one.
RELEASED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COALESCE(SUM(amount),0) FROM work_payments WHERE work_id=$WORK_ID AND payment_type='released'" 2>/dev/null)
check "released total sums the instalments" "$RELEASED" "600000.00"

curl -s -b "$JAR" -o "$TMP/payl.html" "$BASE/payments"
# 1,000,000 sanctioned less 600,000 released leaves 400,000.
if grep -q "400,000.00" "$TMP/payl.html"; then ok "balance derived on the payment list"
else bad "balance not shown correctly on the payment list"; fi
if grep -q "600,000.00" "$TMP/payl.html"; then ok "released total shown on the payment list"
else bad "released total missing from the payment list"; fi

CODE=$(curl -s -b "$JAR" -o "$TMP/hist.html" -w '%{http_code}' "$BASE/payments/history?work_id=$WORK_ID")
check "payment history loads" "$CODE" "200"
if grep -q "प्रथम भुगतान जारी किया गया" "$TMP/hist.html"; then ok "history shows the entry remark"
else bad "history missing entry remarks"; fi
# Each figure names the party that reports it, which is the point of splitting
# the ledger by type rather than keeping one running total.
if grep -q "जारी (जिला द्वारा)" "$TMP/hist.html"; then ok "history attributes released to the district"
else bad "history does not attribute figures to a party"; fi

# A payment must be refused against a work outside the caller's scope, and
# zero or negative amounts must not be accepted.
TOKEN=$(csrf "$BASE/payments")
CODE=$(post_code "$BASE/payments" -d "_token=$TOKEN" -d "work_id=$WORK_ID" \
  -d "payment_type=released" -d "amount=0" -d "payment_date=2026-09-15")
check "zero-amount payment is rejected" "$CODE" "302"
ZERO=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM work_payments WHERE work_id=$WORK_ID AND amount=0" 2>/dev/null)
check "zero-amount payment not stored" "$ZERO" "0"

PAYLOG=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM log_activities WHERE work_id=$WORK_ID AND subject_type='Payment'" 2>/dev/null)
check "payments appear in the work audit trail" "$PAYLOG" "4"

# --- dashboard financial roll-up -----------------------------------------
curl -s -b "$JAR" -o "$TMP/dash2.html" "$BASE/dashboard"
if grep -q "वित्तीय स्थिति" "$TMP/dash2.html"; then ok "financial panel on the dashboard"
else bad "financial panel missing from dashboard"; fi
# Totals must come from the ledger, not from a stored figure. The expected
# figure is derived from the database so the check holds on a re-run, where
# earlier works have already contributed to the total.
EXPECTED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT FORMAT(COALESCE(SUM(amount),0),2) FROM work_payments WHERE payment_type='released'" 2>/dev/null)
if grep -q "$EXPECTED" "$TMP/dash2.html"; then ok "released total rolled up on the dashboard ($EXPECTED)"
else bad "dashboard released total does not match the ledger (want $EXPECTED)"; fi

# An employee sees only their own works, so their roll-up must not include
# money from works assigned to someone else.
EJAR=$(mktemp)
ETOK=$(curl -s -c "$EJAR" -b "$EJAR" "$BASE/login" | grep -o 'name="_token" value="[^"]*"' | head -1 | sed -E 's/.*value="([^"]*)"/\1/')
curl -s -b "$EJAR" -c "$EJAR" -X POST "$BASE/authenticate" \
  -d "_token=$ETOK" -d "login_id=engineer" -d "password=engineer123" -o /dev/null
curl -s -b "$EJAR" -o "$TMP/edash.html" "$BASE/dashboard"
if grep -q "वित्तीय स्थिति" "$TMP/edash.html"; then ok "employee dashboard renders its own roll-up"
else bad "employee dashboard failed to render"; fi
rm -f "$EJAR"

# --- tender save, including the work order date --------------------------
# The tender form always posts to store(): edit() never passes $tender to the
# view, so isset($tender) is false and update() is unreachable from the UI.
RUN=$(date +%s%N | tail -c 7)
TOKEN=$(csrf "$BASE/tender/$WORK_ID/edit")
CODE=$(post_code "$BASE/tender" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "tender_no=TND-A$RUN" \
  -F "tender_release_date=2026-06-01" -F "tender_opening_date=2026-06-20" \
  -F "work_order_date=2026-07-01" -F "work_status=6" -F "remark=स्मोक निविदा")
check "save tender with work order date" "$CODE" "302"
WOD=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT work_order_date FROM tenders WHERE tender_no='TND-A$RUN'" 2>/dev/null)
check "work order date persisted" "$WOD" "2026-07-01"

# The same form with the work order date left blank must still save: the column
# is nullable in production and the field is optional on the form.
TOKEN=$(csrf "$BASE/tender/$WORK_ID/edit")
CODE=$(post_code "$BASE/tender" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "tender_no=TND-B$RUN" \
  -F "tender_release_date=2026-06-01" -F "tender_opening_date=2026-06-20" \
  -F "work_order_date=" -F "work_status=6")
check "tender saves without a work order date" "$CODE" "302"
BLANK=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM tenders WHERE tender_no='TND-B$RUN' AND work_order_date IS NULL" 2>/dev/null)
check "tender without work order date stored" "$BLANK" "1"

# --- optional work-order (Agreement) section below the tender form -------
# tenderChecked=0 (tender applicable, the default) must surface the कार्य आदेश
# section right alongside निविदा -- not gated behind its own stage anymore.
curl -s -b "$JAR" -o "$TMP/tenderpage.html" "$BASE/work-progress/create?work_id=$WORK_ID&work_status=6"
# "वैकल्पिक" (optional) is the work-order panel's own marker -- status 7's
# name "कार्य आदेश जारी" also contains "कार्य आदेश" and would false-positive.
if grep -q "वैकल्पिक" "$TMP/tenderpage.html"; then ok "work-order section shown below tender"
else bad "work-order section missing from tender page"; fi

# None of its fields are mandatory: a submission with only a work order
# number and no agreement_date must still save, not fail validation.
RUN2=$(date +%s%N | tail -c 7)
TOKEN=$(csrf "$BASE/work-progress/create?work_id=$WORK_ID&work_status=6")
CODE=$(post_code "$BASE/work-agreement" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "work_status=6" \
  -F "work_order_no=WO-SMOKE$RUN2" -F "work_order_amount=250000")
check "optional work order saved with no agreement_date" "$CODE" "302"
WO_SAVED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM agreements WHERE work_order_no='WO-SMOKE$RUN2' AND agreement_date IS NULL" 2>/dev/null)
check "work order row stored with a null agreement_date" "$WO_SAVED" "1"

# A work marked "निविदा लागू नहीं है" must not offer the work-order section.
mysql -ularavel -plaravel nirmaan -e "UPDATE works SET tenderChecked=1 WHERE work_id=$WORK_ID" 2>/dev/null
curl -s -b "$JAR" -o "$TMP/notender.html" "$BASE/work-progress/create?work_id=$WORK_ID&work_status=6"
if grep -q "वैकल्पिक" "$TMP/notender.html"; then bad "work-order section shown despite निविदा लागू नहीं है"
else ok "work-order section hidden when tender is not applicable"; fi
mysql -ularavel -plaravel nirmaan -e "UPDATE works SET tenderChecked=0 WHERE work_id=$WORK_ID" 2>/dev/null

# Re-submitting (e.g. via the निविदा edit pencil on the work-detail page,
# after someone skipped the work order the first time) must correct the
# existing agreement in place, not create a second row for the same work.
BEFORE_WO=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM agreements WHERE work_id=$WORK_ID" 2>/dev/null)
TOKEN=$(csrf "$BASE/tender/$WORK_ID/edit")
CODE=$(post_code "$BASE/work-agreement" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "work_status=6" \
  -F "work_order_no=WO-SMOKE-CORRECTED$RUN2" -F "work_order_amount=275000")
check "re-submitted work order saved" "$CODE" "302"
AFTER_WO=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM agreements WHERE work_id=$WORK_ID" 2>/dev/null)
check "work order row count unchanged (updated, not duplicated)" "$AFTER_WO" "$BEFORE_WO"
CORRECTED=$(mysql -ularavel -plaravel nirmaan -N -e \
  "SELECT COUNT(*) FROM agreements WHERE work_id=$WORK_ID AND work_order_no='WO-SMOKE-CORRECTED$RUN2'" 2>/dev/null)
check "existing agreement row carries the corrected value" "$CORRECTED" "1"

# The निविदा edit modal must also show that same work-order data, pre-filled.
curl -s -b "$JAR" -o "$TMP/tenderedit.html" "$BASE/tender/$WORK_ID/edit"
if grep -q "WO-SMOKE-CORRECTED$RUN2" "$TMP/tenderedit.html"; then ok "tender edit modal shows the existing work order, pre-filled"
else bad "tender edit modal does not show the existing work order"; fi

# Mandatory fields must fail validation rather than reach the database and
# raise a NOT NULL error. Closing a work with no close date is the cheapest
# example of the class.
TOKEN=$(csrf "$BASE/work")
CODE=$(post_code "$BASE/work-closed" \
  -F "_token=$TOKEN" -F "work_id=$WORK_ID" -F "close_date=" -F "work_status=11")
check "blank mandatory date is rejected, not a 500" "$CODE" "302"

# --- printable, signable work dossier ------------------------------------
CODE=$(curl -s -b "$JAR" -o "$TMP/dossier.html" -w '%{http_code}' "$BASE/reports/work-dossier/$WORK_ID")
check "work dossier loads" "$CODE" "200"
if grep -q "कार्य विवरण प्रपत्र" "$TMP/dossier.html"; then ok "dossier title present"
else bad "dossier title missing"; fi
# The signature block is the point of the document: it is what makes it usable
# in the physical approval chain.
SIGS=0
for role in "उप अभियंता" "एसडीओ / सहायक अभियंता" "कार्यपालन अभियंता" "सक्षम अधिकारी"; do
  grep -q "$role" "$TMP/dossier.html" && SIGS=$((SIGS+1))
done
check "all four signature blocks present" "$SIGS" "4"
if grep -q "@media print" "$TMP/dossier.html"; then ok "print stylesheet included"
else bad "no print stylesheet"; fi
if grep -q "यह प्रणाली द्वारा तैयार किया गया दस्तावेज़ है" "$TMP/dossier.html"; then ok "system-generated footer present"
else bad "system-generated footer missing"; fi
if grep -q "9876543210" "$TMP/dossier.html"; then ok "officer contacts carried into the dossier"
else bad "officer contacts missing from dossier"; fi
if grep -q "work-dossier" "$TMP/detail.html" 2>/dev/null || curl -s -b "$JAR" "$BASE/reports/work-details/$WORK_ID" | grep -q "work-dossier"; then
  ok "print button linked from work detail"
else bad "no print button on work detail"; fi

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

# The printable dossier builds its own photo list straight from work_progress
# and must also pick up work_progress_images entries, not just the legacy
# single upload_file column.
curl -s -b "$JAR" -o "$TMP/dossier_photos.html" "$BASE/reports/work-dossier/$WORK_ID"
if grep -qE 'images/Work-Progress/[a-f0-9]+\.png' "$TMP/dossier_photos.html"; then ok "progress photo rendered on the dossier"
else bad "progress photo missing from the dossier"; fi

# --- work-progress status dropdown must list every real status -----------
# CWorkStatus was 4 hardcoded options unrelated to work_statuses. It now
# lists every row in the table, and only status 10/11/12 must reveal the
# complete/closed/reject forms — everything else keeps the ongoing-progress
# form visible.
STATUS_COUNT=$(mysql -ularavel -plaravel nirmaan -N -e "SELECT COUNT(*) FROM work_statuses" 2>/dev/null)
curl -s -b "$JAR" -o "$TMP/wpstatus.html" "$BASE/work-progress/create?work_id=$WORK_ID&work_status=9"
# +1 for the "--वर्तमान स्थिति चुनें--" placeholder option.
RENDERED=$(awk '/id="CWorkStatus"/,/<\/select>/' "$TMP/wpstatus.html" | grep -c '<option value="[0-9]')
check "status dropdown lists every work_statuses row" "$RENDERED" "$((STATUS_COUNT + 1))"

if grep -q 'id="wpStagesForm"' "$TMP/wpstatus.html" && grep -q 'id="wpCompleteForm"' "$TMP/wpstatus.html" \
   && grep -q 'id="wpClosedForm"' "$TMP/wpstatus.html" && grep -q 'id="wpRejectForm"' "$TMP/wpstatus.html"; then
  ok "all four progress sub-forms present on the page"
else
  bad "one or more progress sub-forms missing from the page"
fi

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
