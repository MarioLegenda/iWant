<?php


use Phinx\Migration\AbstractMigration;

class CreateEbayRootCategoriesTable extends AbstractMigration
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
        $sql = 'CREATE TABLE ebay_root_categories (id INT AUTO_INCREMENT NOT NULL, native_taxonomy_id INT DEFAULT NULL, global_id VARCHAR(255) NOT NULL, category_id VARCHAR(255) NOT NULL, category_id_path VARCHAR(255) NOT NULL, category_level SMALLINT NOT NULL, category_name VARCHAR(255) NOT NULL, category_name_path VARCHAR(255) NOT NULL, category_parent_id VARCHAR(255) NOT NULL, leaf_category TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_17285EFDE3EB2712 (native_taxonomy_id), INDEX category_id_idx (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB';

        $this->execute($sql);
    }
}
