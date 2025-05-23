# ── Stage 1: Build ─────────────────────────────────────────────
FROM node:22-bullseye AS build
WORKDIR /app

# Copia dependencias para cachear
COPY package.json package-lock.json* ./
RUN npm install

# Copia el resto y genera el build Vite
COPY . .
RUN npm run build

# ── Stage 2: Runtime ───────────────────────────────────────────
FROM nginx:1.27-alpine

# (Opcional) Config para fallback de SPA
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Sirve los archivos estáticos desde dist/
COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80
CMD ["nginx","-g","daemon off;"]
