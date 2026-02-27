# Deploy ke Vercel

## Langkah-langkah

### 1. Push ke GitHub
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/username/bubub-movie.git
git push -u origin main
```

### 2. Import di Vercel
- Buka https://vercel.com/new
- Import repository GitHub kamu
- **Framework Preset**: Other
- **Build Command**: kosongkan (sudah ada di vercel.json)
- **Output Directory**: public

### 3. Set Environment Variables di Vercel Dashboard
Buka Settings → Environment Variables, tambahkan satu per satu:

| Key | Value |
|-----|-------|
| APP_NAME | Bubub Movie |
| APP_ENV | production |
| APP_KEY | base64:tAF+Q5H3Tgrknpt6vaH8qbXYNi9Ys9DG/STpvOFUzqI= |
| APP_DEBUG | false |
| APP_URL | https://nama-project.vercel.app |
| DB_CONNECTION | pgsql |
| DB_HOST | ep-lingering-darkness-a19mgj2b-pooler.ap-southeast-1.aws.neon.tech |
| DB_PORT | 5432 |
| DB_DATABASE | neondb |
| DB_USERNAME | neondb_owner |
| DB_PASSWORD | npg_31VDHYPQpovi |
| DB_SSLMODE | require |
| OMDB_API_KEY | ec7c3b26 |
| SESSION_DRIVER | cookie |
| CACHE_STORE | array |
| LOG_CHANNEL | stderr |

### 4. Deploy!
Klik Deploy — selesai.

## Catatan Penting
- **Jangan** gunakan `npm run build` — project ini tidak pakai Vite/Node
- File `.env` lokal **tidak** perlu di-push ke GitHub
- Semua env variable diset lewat Vercel Dashboard
