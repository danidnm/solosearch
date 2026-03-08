# Reglas para Modelos de Base de Datos

Todos los módulos que requieran persistencia deben seguir el patrón **Model-Resource-Repository-Factory**:

1. **Estructura Cuádruple**: Cada entidad debe tener cuatro clases:
   - `Model/[Entity].php`: Contiene solo los datos y la lógica de negocio. Extiende de `AbstractModel`.
   - `Model/Resource/[Entity].php`: Maneja toda la persistencia (lectura/escritura) y comunicación con la DB. Extiende de `DbModelAbstract`.
   - `Model/[Entity]Repository.php`: Es el punto de entrada para obtener y guardar entidades.
   - `Model/[Entity]Factory.php`: Responsable de crear nuevas instancias de la entidad, utilizando el contenedor de dependencias.

2. **Responsabilidad de Creación**:
   - El **Factory** es el único encargado de instanciar un nuevo modelo.
   - El **Repository** utiliza el **Factory** para crear nuevas estancias cuando sea necesario.

3. **Responsabilidad de Persistencia**:
   - El **Model** NO debe tener métodos `load()` o `save()`.
   - La lectura y escritura es responsabilidad exclusiva del **Resource Model**.
   - El **Model** recibe el recurso por inyección en el constructor.

4. **Gestión en el Repository (Identity Map)**:
   - El repositorio debe evitar lecturas duplicadas de la misma entidad desde la base de datos durante la misma ejecución.
   - Debe tener una propiedad protegida `$entities` (array) para almacenar las instancias ya cargadas.
   - Antes de una carga por ID, debe comprobar si la entidad ya existe en `$entities`.
   - Al guardar o cargar una entidad, debe actualizarla en `$entities`.
