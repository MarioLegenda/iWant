<?php


use Phinx\Migration\AbstractMigration;

class CreateApplicationShopsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $sql = 'CREATE TABLE application_shops (id INT AUTO_INCREMENT NOT NULL, normalized_category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, application_name VARCHAR(255) NOT NULL, global_id VARCHAR(255) DEFAULT NULL, un_categorised TINYINT(1) NOT NULL, marketplace VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C0EE80F9CDCA6126 (normalized_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB';

        $this->execute($sql);
    }
}
