# Calculadora de Precio Indexado de Energía

Aplicación desarrollada con **Laravel**, **React** y **MySQL** para calcular el precio indexado de la energía a partir de consumos horarios y precios horarios.

## Tecnologías utilizadas

### Backend
- Laravel
- PHP 8.x
- API REST
- Eloquent ORM

### Frontend
- React
- Axios
- Vite

### Base de datos
- MySQL

---

# Arquitectura del sistema

La aplicación está dividida en tres capas principales.

## Backend

API REST desarrollada en **Laravel**, responsable de:

- Gestión de la lógica de negocio.
- Acceso a la base de datos.
- Cálculo del precio indexado.
- Validación de datos.
- Gestión de errores.

---

## Frontend

Aplicación desarrollada en **React** que consume la API REST y permite al usuario:

- Introducir el rango de fechas.
- Introducir la fórmula de cálculo.
- Ejecutar el cálculo.
- Visualizar el resultado obtenido.

La comunicación con el backend se realiza mediante peticiones HTTP utilizando JSON.

---

## Base de datos

Se utiliza **MySQL** para almacenar:

- Consumos horarios.
- Precios horarios.

---

# Base de datos

## Tabla `consumptions`

Almacena el consumo horario de energía de cada día.

| Campo | Tipo | Descripción |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Identificador |
| date | DATE | Fecha única |
| h1 - h25 | DOUBLE | Consumo horario (kWh) |

---

## Tabla `prices`

Almacena el precio horario del segmento **OMIE_MD**.

| Campo | Tipo | Descripción |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Identificador |
| date | DATE | Fecha única |
| h1 - h25 | DOUBLE | Precio horario (€/kWh) |

---

# API REST

## Endpoint

### POST `/api/calculate`

Calcula el precio indexado para un rango de fechas utilizando una fórmula configurable.

### Parámetros

```json
{
    "start_date": "2025-01-01",
    "end_date": "2025-01-0",
    "formula": "[OMIE_MD] * 1.05 + 0.02"
}
```

### Respuesta

```json
{
    "price": 0.06626...
}
```

---

## Códigos de respuesta

| Código | Descripción |
|---------|-------------|
| 200 | Cálculo realizado correctamente |
| 400 | Datos inválidos |
| 404 | No existen consumos o precios para el rango indicado |
| 500 | Error interno durante el cálculo |

---

# Lógica del cálculo

Para cada hora comprendida entre **start_date** y **end_date** se realiza el siguiente proceso.

## 1. Evaluación de la fórmula

Se sustituye el valor del marcador:

```
[OMIE_MD]
```

por el precio correspondiente a esa hora.


---

## 2. Cálculo del importe horario

```
importe_hora = precio_evaluado × consumo_hora
```

---

## 3. Suma de importes

```
suma_importes = Σ importe_hora
```

---

## 4. Suma de consumos

```
suma_consumos = Σ consumo_hora
```

---

## 5. Precio indexado

```
precio_indexado = suma_importes / suma_consumos
```

---

# Instalación

## Clonar el repositorio

```bash
git clone <repositorio>
```

---

## Backend (Laravel)

Instalar dependencias

```bash
composer install
```

Copiar el archivo de entorno

```bash
cp .env.example .env
```

Generar la clave de la aplicación

```bash
php artisan key:generate
```

Configurar la conexión con MySQL en el archivo `.env`.

Ejecutar las migraciones

```bash
php artisan migrate
```

Iniciar el servidor

```bash
php artisan serve
```

---

## Frontend (React)

Instalar dependencias

```bash
npm install
```

Ejecutar la aplicación

```bash
npm run dev
```

---

# Consideraciones

- La fecha es única en ambas tablas.
- El cálculo no almacena resultados en la base de datos.
- Toda la lógica de cálculo reside en el backend.
- El frontend únicamente consume la API REST.
- El sistema devuelve códigos HTTP adecuados según el resultado de la operación.

---

# Autor

Proyecto desarrollado como prueba técnica utilizando **Laravel + React + MySQL**.