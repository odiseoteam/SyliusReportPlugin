<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211013183052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Odiseo SyliusReportPlugin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS odiseo_report (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, code VARCHAR(255) NOT NULL, renderer VARCHAR(255) NOT NULL, renderer_configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', data_fetcher VARCHAR(255) NOT NULL, data_fetcher_configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_840BB13D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE odiseo_report');
    }
}
