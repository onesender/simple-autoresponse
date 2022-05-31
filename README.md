# Simple Autoresponse

Script untuk membuat sistem autoresponse sederhana untuk OneSender.

## Cara Install

1. Install dependency dengan composer
```
composer install
```

2. Buat pengaturan di file `config.yaml`.

3. Upload file rule untuk trigger pesan dan simpan di folder `./template/`.

4. Upload ke webhosting.

5. Arahkan webhook OneSender ke link script.


## Security

Jika script ini diinstall di sub folder (contoh: https://domainsaya.com/simple-autoresponse/), harap buat pengaturan agar file `config.yaml` tidak bisa diakses orang lain.

Pengaturan keamanan dapat dibuat via file `.htaccess`.

## Kontak info

https://onesender.net