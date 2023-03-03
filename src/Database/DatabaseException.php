namespace NeeZiaa\Database;

abstract class Database
{
    public static function create(string $type, array $config): ?DatabaseInterface
    {
        switch ($type) {
            case 'mysql':
                return new MysqlDatabase($config['host'], $config['database'], $config['username'], $config['password']);
            // Add support for other database types here
            default:
                throw new DatabaseException('Unsupported database type: ' . $type);
        }
    }
}
