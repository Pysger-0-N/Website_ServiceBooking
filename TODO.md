# TODO

## Step 1 (P0)
- [x] Audit masalah di BookingController: pastikan relasi/namespace model konsisten.
- [ ] Fix `BookingController@selectProvider` bila ada reference model yang salah.
- [ ] Tambahkan relasi `categories()` di `ProviderProfileModel` agar `providerProfile->categories()` bisa digunakan.

## Step 2 (P1)
- [ ] Validasi service milik category yang sesuai provider.
- [ ] Rapikan validasi booking_time (date_format H:i atau normalisasi).
- [ ] Tinjau bentrok status (pending/confirmed/on_progress) sesuai workflow.

