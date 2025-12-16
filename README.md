# payment_coding_test
Prueba técnica de desarrollador php en arquitectura hexagonal, contextualizada en el proceso de notificaciones de pagos online.

Este proyecto implementa un sistema de notificaciones de pagos en PHP 8.2 siguiendo
principios de arquitectura hexagonal (Ports & Adapters), con especial foco en:
- separación de responsabilidades
- seguridad en la comunicación (JWT)
- testabilidad
- mantenibilidad

La solución simula el envío de notificaciones de pagos firmadas criptográficamente mediante HTTP POST.

---

## Requisitos

- Docker
- Docker Compose

No es necesario tener PHP ni Composer instalados en local.

---

## Puesta en marcha

Clona el repositorio y sitúate en la raíz del proyecto:

```bash
git clone https://github.com/osruigi/payment_coding_test.git
cd payment_coding_test
```

Construye y levanta el entorno:
```bash
docker compose up -d --build
```

Accede al contenedor de la aplicación:
```bash
docker compose exec app bash
```

---

## Enviar una notificación (CLI)

Define las variables de entorno para la firma y el endpoint de destino:
```bash
export JWT_SECRET="super-secret"
export ENDPOINT="https://site/tu-endpoint"
```

Ejecuta el flujo completo desde la CLI:
```bash
php bin/notify --amount=100 --status=completed --creditor=ES123 --debtor=ES456
```

Detalles:
- Cuerpo JSON enviado:
  ```json
  {
    "amount": 100,
    "status": "completed",
    "creditor_account": "ES123",
    "debtor_account": "ES456",
    "notification_id": "<uuid>"
  }
  ```
- Cabecera `Signature`: JWT HS256 firmado con `JWT_SECRET`, cuyo payload contiene el objeto `payment` con el mismo cuerpo enviado.
- El `notification_id` se genera como UUID v4 si no se aporta `--notification-id`.

---

## Ejecutar los tests

Los tests se ejecutan mediante PHPUnit dentro del contenedor Docker.
```bash
./vendor/bin/phpunit
```

Los tests incluyen:
- tests unitarios de dominio
- tests unitarios de infraestructura (JWT, HTTP)
- tests de integración del flujo completo de notificación
