<?php

declare(strict_types=1);
/**
 * Migration.
 */
namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200625151317 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, photo_id INT NOT NULL, user_id INT UNSIGNED NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_5F9E962A7E9E4C8C (photo_id), INDEX IDX_5F9E962AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galleries (id INT AUTO_INCREMENT NOT NULL, name_gallery VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, gallery_id INT NOT NULL, author_id INT UNSIGNED NOT NULL, link VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_876E0D94E7AF8F (gallery_id), INDEX IDX_876E0D9F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos_tags (photo_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F8A626C77E9E4C8C (photo_id), INDEX IDX_F8A626C7BAD26311 (tag_id), PRIMARY KEY(photo_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D94E7AF8F FOREIGN KEY (gallery_id) REFERENCES galleries (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE photos_tags ADD CONSTRAINT FK_F8A626C77E9E4C8C FOREIGN KEY (photo_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_tags ADD CONSTRAINT FK_F8A626C7BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D94E7AF8F');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7E9E4C8C');
        $this->addSql('ALTER TABLE photos_tags DROP FOREIGN KEY FK_F8A626C77E9E4C8C');
        $this->addSql('ALTER TABLE photos_tags DROP FOREIGN KEY FK_F8A626C7BAD26311');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9F675F31B');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE galleries');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE photos_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
    }
}
