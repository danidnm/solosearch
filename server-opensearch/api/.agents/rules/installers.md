# Reglas para Instaladores (Setup/Install)

Todos los archivos `Install.php` dentro de los módulos deben seguir estas reglas:

1. **Versionado Incremental**: Los cambios en la base de datos no se deben hacer mediante comprobaciones manuales de "si existe la tabla", sino basándose en la versión del módulo.
2. **Uso de `version_compare`**: El método `install($version)` debe usar `version_compare($version, 'X.X.X', '<')` para decidir qué bloques de código ejecutar.
3. **Bumping de Versión**: Cada cambio en el esquema debe ir acompañado de un incremento de versión en el archivo `etc/config.php` del módulo correspondiente.
4. **Métodos Atómicos**: Cada actualización de versión debería estar encapsulada en su propio método privado (ej: `addRoleColumn()`, `createInitialTables()`).
