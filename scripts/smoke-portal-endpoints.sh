#!/usr/bin/env bash
set -euo pipefail

# Lightweight HTTP smoke checks for dual central portals (default artisan ports).
HOST="${HOST:-127.0.0.1}"
ADMIN_PORT="${ADMIN_PORT:-8000}"
PUBLIC_PORT="${PUBLIC_PORT:-8005}"

chk() {
  local url="$1"
  local want="${2:-200}"
  local code
  code="$(curl -sS -o /dev/null -w "%{http_code}" "$url")"
  if [[ "$code" == "$want" ]] || [[ "$want" == "302" && "$code" == "301" ]]; then
    printf 'OK %-3s %s\n' "$code" "$url"
  else
    printf 'FAIL %-3s (expected %s) %s\n' "$code" "$want" "$url"
    exit 1
  fi
}

echo "Smoke: admin (${HOST}:${ADMIN_PORT})"
chk "http://${HOST}:${ADMIN_PORT}/up"
chk "http://${HOST}:${ADMIN_PORT}/"
chk "http://${HOST}:${ADMIN_PORT}/login"

echo ""
echo "Smoke: public (${HOST}:${PUBLIC_PORT})"
chk "http://${HOST}:${PUBLIC_PORT}/up"
chk "http://${HOST}:${PUBLIC_PORT}/"
chk "http://${HOST}:${PUBLIC_PORT}/about"
chk "http://${HOST}:${PUBLIC_PORT}/explore/accommodations"
chk "http://${HOST}:${PUBLIC_PORT}/register/guest"
chk "http://${HOST}:${PUBLIC_PORT}/register/owner"

echo ""
echo "All smoke checks passed."
