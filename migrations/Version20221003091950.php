<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003091950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission_partner (permission_id INT NOT NULL, partner_id INT NOT NULL, INDEX IDX_63ADF41FED90CCA (permission_id), INDEX IDX_63ADF419393F8FE (partner_id), PRIMARY KEY(permission_id, partner_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_structure (permission_id INT NOT NULL, structure_id INT NOT NULL, INDEX IDX_4DF61986FED90CCA (permission_id), INDEX IDX_4DF619862534008B (structure_id), PRIMARY KEY(permission_id, structure_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permission_partner ADD CONSTRAINT FK_63ADF41FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_partner ADD CONSTRAINT FK_63ADF419393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_structure ADD CONSTRAINT FK_4DF61986FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_structure ADD CONSTRAINT FK_4DF619862534008B FOREIGN KEY (structure_id) REFERENCES structure (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission_partner DROP FOREIGN KEY FK_63ADF41FED90CCA');
        $this->addSql('ALTER TABLE permission_partner DROP FOREIGN KEY FK_63ADF419393F8FE');
        $this->addSql('ALTER TABLE permission_structure DROP FOREIGN KEY FK_4DF61986FED90CCA');
        $this->addSql('ALTER TABLE permission_structure DROP FOREIGN KEY FK_4DF619862534008B');
        $this->addSql('DROP TABLE permission_partner');
        $this->addSql('DROP TABLE permission_structure');
    }
}
