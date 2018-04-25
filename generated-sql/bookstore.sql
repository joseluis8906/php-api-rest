
BEGIN;

-----------------------------------------------------------------------
-- book
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "book" CASCADE;

CREATE TABLE "book"
(
    "id" serial NOT NULL,
    "title" VARCHAR NOT NULL,
    "isbn" VARCHAR NOT NULL,
    "publisher_id" INTEGER,
    "author_id" INTEGER,
    PRIMARY KEY ("id"),
    CONSTRAINT "unique_isbn" UNIQUE ("isbn")
);

-----------------------------------------------------------------------
-- author
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "author" CASCADE;

CREATE TABLE "author"
(
    "id" serial NOT NULL,
    "first_name" VARCHAR NOT NULL,
    "last_name" VARCHAR NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publisher
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publisher" CASCADE;

CREATE TABLE "publisher"
(
    "id" serial NOT NULL,
    "name" VARCHAR NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "book" ADD CONSTRAINT "book_fk_35872e"
    FOREIGN KEY ("publisher_id")
    REFERENCES "publisher" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "book" ADD CONSTRAINT "book_fk_ea464c"
    FOREIGN KEY ("author_id")
    REFERENCES "author" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

COMMIT;
