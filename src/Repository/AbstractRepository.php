<?php

namespace Riotoon\Repository;

use PDOException;

/**
 * This interface represents the different operations on the CRUD
 * 
 * Create   : `add()`
 * 
 * Read    : `findAll()`
 * 
 * Update : `edit()`
 * 
 * Delete : `delete()`
 */
interface AbstractRepository
{
    /**
     * Adds a new information in the table to the database.
     * @return void
     * @return mixed If a return value exists
     * @throws PDOException if an error occurs during insertion.
     */
    public function add();

    /**
     * Retrieves all table information from the table in the database.
     * @return array An array of Class (table) Objects representing the entire Class (table)
     * @throws PDOException if an error occurs during retrieval.
     */
    public function findAll();

    /**
     * Updates the informations of an existing table in the database.
     * @param mixed $id The ID of the table field to update
     * @return void
     * @throws PDOException if an error occurs during update.
     */
    public function edit($id);

    /**
     * Summary of delete
     * @param mixed $id The ID of the table field to delete
     * @return void
     * @throws PDOException if an error occurs during delete.
     */
    public function delete($id);
}