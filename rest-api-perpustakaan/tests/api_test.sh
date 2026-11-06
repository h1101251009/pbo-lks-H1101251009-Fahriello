#!/bin/bash

BASE_URL="http://localhost:8000/api/books"
JSON="Content-Type: application/json"

echo "== Test 1: GET semua buku =="
curl -s "$BASE_URL" | jq .
echo

echo "== Test 2: POST buku baru =="
NEW_ID=$(curl -s -X POST "$BASE_URL" -H "$JSON" \
  -d '{"judul":"Test Buku","penulis":"Penulis Test","tahun":2020,"stok":7}' \
  | jq -r '.data.id')
echo "ID baru: $NEW_ID"
echo

echo "== Test 3: GET buku ID $NEW_ID =="
curl -s "$BASE_URL/$NEW_ID" | jq .
echo

echo "== Test 4: PUT ubah buku =="
curl -s -X PUT "$BASE_URL/$NEW_ID" -H "$JSON" \
  -d '{"judul":"Test Buku Revisi","penulis":"Penulis Test","tahun":2021,"stok":9}' | jq .
echo

echo "== Test 5: DELETE buku ID $NEW_ID =="
curl -s -X DELETE "$BASE_URL/$NEW_ID" | jq .
echo

echo "== Test 6: Validasi error =="
curl -s -X POST "$BASE_URL" -H "$JSON" -d '{"judul":""}' | jq .
