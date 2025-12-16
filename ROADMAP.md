# ROADMAP – Payment Notification System

Este documento describe las fases de desarrollo del sistema de notificaciones de pagos.
Cada fase está pensada para producir código funcional y versionable, siguiendo principios
de arquitectura hexagonal (Ports & Adapters), PHP 8.2 y buenas prácticas de testing.

---

## Phase 0 – Project bootstrap

**Objetivo**
Inicializar el proyecto con una base sólida y estándar.

**Tareas**
- Crear el entorno de trabajo con Docker (PHP 8.2 + Composer + PHPUnit)
- Inicializar proyecto con Composer
- Configurar autoload PSR-4
- Configurar PHPUnit
- Crear estructura base de carpetas

**Reglas**
- Docker se utiliza únicamente como entorno de ejecución
- No se introduce complejidad innecesaria (bases de datos, colas, etc.)
- El proyecto debe poder ejecutarse con un único `docker compose up`

**Resultado**
Proyecto ejecutable con `vendor/` y tests listos para añadirse.

**Commit sugerido**
chore: initial project setup with composer and phpunit

---

## Phase 1 – Domain layer

**Objetivo**
Definir el modelo de dominio sin dependencias externas.

**Tareas**
- Crear entidad `Payment`
- Definir enum `PaymentStatus`
- Implementar `notification_id` como UUID (Value Object)
- Usar tipado estricto y propiedades inmutables

**Reglas**
- El dominio no debe depender de ninguna librería externa
- No incluir lógica de infraestructura (HTTP, JWT, etc.)

**Commit sugerido**
feat(domain): add Payment entity and value objects

---

## Phase 2 – Application layer (Use Case & Ports)

**Objetivo**
Orquestar el envío de notificaciones mediante un caso de uso.

**Tareas**
- Crear `NotifyPaymentUseCase`
- Definir puerto `NotificationSenderPort`
- Definir puerto `SignatureGeneratorPort`
- Inyectar dependencias mediante constructor

**Reglas**
- La capa Application solo conoce interfaces
- No hay implementaciones concretas

**Commit sugerido**
feat(application): add notify payment use case and ports

---

## Phase 3 – Infrastructure layer

**Objetivo**
Implementar los adaptadores concretos para HTTP, JWT y UUID.

**Tareas**
- Implementar `JwtSignatureGenerator` usando JWT
- Implementar `HttpNotificationSender` usando HTTP POST JSON
- Añadir cabecera `Signature` con el JWT firmado
- Serializar correctamente la entidad Payment

**Reglas**
- Infrastructure depende de Application
- JWT debe firmar el cuerpo completo de la request

**Commit sugerido**
feat(infrastructure): implement http sender and jwt signature

---

## Phase 4 – Testing

**Objetivo**
Validar comportamiento y seguridad del sistema.

**Tareas**
- Tests unitarios del dominio
- Tests del generador de firmas
- Tests de integración del flujo completo
- Tests de error (firma inválida, envío fallido)

**Reglas**
- Separar tests unitarios e integración
- No testear detalles internos innecesarios
- Los tests deben poder ejecutarse tanto en local como dentro del contenedor Docker

**Commit sugerido**
test: add unit and integration tests for notification flow

---

## Phase 5 – Documentation

**Objetivo**
Explicar el uso y las decisiones técnicas del proyecto.

**Tareas**
- Crear README.md
- Documentar el uso de Docker y docker compose
- Explicar cómo ejecutar el proyecto
- Explicar cómo ejecutar los tests
- Documentar decisiones arquitectónicas
- Añadir posibles mejoras futuras

**Commit sugerido**
docs: add README with usage and design decisions
