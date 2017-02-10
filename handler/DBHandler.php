<?php

require_once dirname(__FILE__) . '/DBConnect.php';

class DbHandler extends dbConn
{

    var $company_id;
    var $company_name;
    var $company_username;
    var $company_password;

    private $conn;

    function __construct()
    {
        $this->conn = dbConn::getConnection();;
    }

    //USER CRUD

    //register
    function registerUser($email, $password, $firstname, $lastname, $contact, $birthday, $address, $image)
    {

        $checkEmailStatement = $this->conn->prepare(
            "SELECT email from user_table where email like :email"
        );

        $checkEmailStatement->execute(array(':email' => $email));
        if ($checkEmailStatement->rowCount()) {
            return 'emailExisting';
        } else {

            $statement = $this->conn->prepare(
                "INSERT INTO `user_table` 
                          (`email`, 
                          `password`, 
                          `firstname`, 
                          `lastname`, 
                          `contact`, 
                          `birthday`, 
                          `address`,  
                          `image`) 
                          VALUES (
                          :email,:password,:firstname,:lastname,:contact,:birthday,:address,:image);"
            );


            $statement->execute(array(':email' => $email,
                ':password' => $password,
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':contact' => $contact,
                ':birthday' => $birthday,
                ':address' => $address,
                ':image' => $image));

            if ($statement) {
                return 'success';
            } else {
                return 'failed';
            }
        }

    }

    //login
    function loginUser($email, $password)
    {

        $checkEmailStatement = $this->conn->prepare(
            "SELECT email from user_table where email like :email"
        );
        $checkEmailStatement->execute(array(':email' => $email));

        if ($checkEmailStatement->rowCount()) {
            $checkPasswordStatement = $this->conn->prepare(
                "SELECT password from user_table where email like :email"
            );
            $checkPasswordStatement->execute(array(':email' => $email));
            if ($checkPasswordStatement->Fetch(PDO::FETCH_OBJ)->password == $password) {
                return 'success';
            } else {
                return 'wrongPassword';
            }

        } else {
            return 'doesNotExist';
        }

    }

    //login
    function loginCompany($company_username , $company_password)
    {

        $checkUsername = $this->conn->prepare(
            "SELECT company_username from company where company_username like :company_username"
        );
        $checkUsername->execute(array(':company_username' => $company_username));

        if ($checkUsername->rowCount()) {
            $checkPasswordStatement = $this->conn->prepare(
                "SELECT * from company where company_username like :company_username"
            );
            $checkPasswordStatement->execute(array(':company_username' => $company_username));

            $company = $checkPasswordStatement->Fetch(PDO::FETCH_OBJ);



            if ($company->company_password == $company_password) {

            $this->company_id = $company->company_id;
            $this->company_name = $company->company_name;
            $this->company_username = $company->company_username;
            $this->company_password = $company->company_password;

                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    //getUserByEmail
    function getUserByEmail($email)
    {

        $getUserStatement = $this->conn->prepare(
            "SELECT * from user_table where email like :email"
        );
        $getUserStatement->execute(array(':email' => $email));

        return $getUserStatement;

    }

    function getAllPackages()
    {

        $getAllPackage = $this->conn->prepare(
            "SELECT * from packages"
        );
        $getAllPackage->execute();

        return $getAllPackage;

    }

    //GCM TOKEN

    //saveUserToken
    function saveUserToken($user_id, $reg_token)
    {
        $saveTokenStatement = $this->conn->prepare(
            "INSERT INTO `user_token_table` (`user_id`, `reg_token`) VALUES (:user_id, :reg_token)"
        );
        $saveTokenStatement->execute(array(':user_id' => $user_id, ':reg_token' => $reg_token));


        return $saveTokenStatement;

    }

    //deleteUserToken
    function deleteUserToken($reg_token)
    {
        $deleteUserToken = $this->conn->prepare(
            "delete from user_token_table where reg_token = :reg_token"
        );
        $deleteUserToken->execute(array(':reg_token' => $reg_token));

        return $deleteUserToken;

    }


    //PACKAGE CRUD

    function addPackage($package_name, $package_type, $package_price, $company_id, $package_image)
    {
        $savePackageStatement = $this->conn->prepare(
            "INSERT INTO `packages` (`package_id`, `package_name`, `package_type`, `package_price`, `company_id`,`image_directory`) 
            VALUES (NULL, :package_name, :package_type, :package_price, :company_id,:package_image);"
        );

        var_dump($savePackageStatement);
        $savePackageStatement->execute(array(':package_name' => $package_name,
            ':package_type' => $package_type,
            ':package_price' => $package_price,
            ':company_id' => $company_id,
            ':package_image' => $package_image));


        return $savePackageStatement;
    }

    function deletePackage($package_id)
    {


        $deletePackageStatement = $this->conn->prepare(
            "delete from packages where package_id = :package_id"
        );
        $deletePackageStatement->execute(array(':package_id' => $package_id));


        return $deletePackageStatement;
    }

    function editPackage($package_id, $package_name, $package_type, $package_price, $company_id, $package_image)
    {

        $updatePackage = $this->conn->prepare(
            "UPDATE `packages` 
            SET 
             `package_name` = :package_name,
             `package_type` = :package_type,
             `package_price` = :package_price,
             `company_id` = :company_id,
             `image_directory` = :package_image
             
             WHERE `packages`.`package_id` = :package_id;"
        );
        $updatePackage->execute(array(
            ':package_name' => $package_name,
            ':package_type' => $package_type,
            ':package_price' => $package_price,
            ':company_id' => $company_id,
            ':package_id' => $package_id,
            ':package_image' => $package_image));


        return $updatePackage;
    }

    function getAllCompanyPackages($company_id)
    {

        $getAllCompanyPackages = $this->conn->prepare(
            "SELECT * from packages where company_id = :company_id"
        );
        $getAllCompanyPackages->execute(array(':company_id' => $company_id));

        return $getAllCompanyPackages->FetchAll(PDO::FETCH_OBJ);

    }

    function getDistinctPackageType(){
        $statement = $this->conn->prepare(
            "SELECT DISTINCT package_type FROM `packages`"
        );
        $statement->execute();

        return $statement->FetchAll(PDO::FETCH_OBJ);
    }

    function getPackageById($package_id)
    {

        $getPackageStatement = $this->conn->prepare(
            "SELECT * from packages where package_id = :package_id"
        );
        $getPackageStatement->execute(array(':package_id' => $package_id));

        return $getPackageStatement->Fetch(PDO::FETCH_OBJ);

    }

    //COMPANY CRUD

    function addCompany($company_name, $company_username, $company_password)
    {


        $saveCompanyStatement = $this->conn->prepare(
            "INSERT INTO `company` (`company_id`, `company_name`, `company_username`, `company_password`) VALUES (NULL, :company_name, :company_username, :company_username);"
        );
        $saveCompanyStatement->execute(array(':company_name' => $company_name,
            ':company_username' => $company_username,
            ':$company_password' => $company_password));


        return $saveCompanyStatement;
    }

    function editCompany($company_id, $company_name, $company_username, $company_password)
    {

        $editCompanyStatement = $this->conn->prepare(
            "UPDATE `company` 
            SET 
             `company_name` = :company_name,
             `company_username` = :company_username,
             `company_password` = :company_password
             
             WHERE `company`.`company_id` = :company_id;"
        );


        $editCompanyStatement->execute(array(
            ':company_id' => $company_id,
            ':company_name' => $company_name,
            ':company_username' => $company_username,
            ':company_password' => $company_password));


        return $editCompanyStatement;
    }

    function deleteCompany($company_id)
    {

        $deleteCompanyStatement = $this->conn->prepare(
            "delete from company where company_id = :company_id"
        );
        $deleteCompanyStatement->execute(array(':company_id' => $company_id));


        return $deleteCompanyStatement;
    }

    function getAllCompany()
    {

        $getAllCompanyStatement = $this->conn->prepare(
            "SELECT * FROM company;"
        );
        $getAllCompanyStatement->execute();


        return $getAllCompanyStatement;
    }


    //CATEGORY CRUD

    function addCategory($package_id, $category_name, $category_description)
    {


        $saveCategoryStatement = $this->conn->prepare(
            "INSERT INTO `category` (`category_id`, `package_id`, `category_name`, `category_description`) VALUES (NULL, :package_id, :category_name, :category_description);"
        );
        $saveCategoryStatement->execute(array(':package_id' => $package_id,
            ':category_name' => $category_name,
            ':category_description' => $category_description));


        return $saveCategoryStatement;
    }

    function editCategory($category_id, $package_id, $category_name, $category_description)
    {

        $editCategoryStatement = $this->conn->prepare(
            "UPDATE `category` 
            SET 
             `package_id` = :package_id,
             `category_name` = :category_name,
             `category_description` = :category_description
             
             WHERE `category`.`category_id` = :category_id;"
        );


        $editCategoryStatement->execute(array(
            ':package_id' => $package_id,
            ':category_name' => $category_name,
            ':category_description' => $category_description,
            ':category_id' => $category_id));


        return $editCategoryStatement;
    }

    function deleteCategory($category_id)
    {
        $deleteCategoryStatement = $this->conn->prepare(
            "delete from category where category_id = :category_id"
        );
        $deleteCategoryStatement->execute(array(':category_id' => $category_id));


        return $deleteCategoryStatement;
    }

    function getAllCategoryPerPackage($package_id)
    {
        $getAllCategoryStatement = $this->conn->prepare(
            "select * from category where package_id = :package_id"
        );
        $getAllCategoryStatement->execute(array(':package_id' => $package_id));


        return $getAllCategoryStatement->FetchAll(PDO::FETCH_OBJ);
    }

    //EVENTS CRUD

    function addEvent($user_id, $package_id, $event_name, $event_date_from, $event_date_to, $event_description, $event_tags, $loc_lat, $loc_long, $loc_name, $image_directory)
    {


        $saveEventStatement = $this->conn->prepare(
            "INSERT INTO `events` (
            `event_id`, 
            `user_id`, 
            `package_id`, 
            `event_name`, 
            `event_date_from`, 
            `event_date_to`,
            `event_description`,
            `event_tags`,
            `loc_lat`,
            `loc_long`,
            `loc_name`,
            `image_directory`) 
            VALUES (
            NULL, 
            :user_id, 
            :package_id,
            :event_name,
            :event_date_from,
            :event_date_to,
            :event_description,
            :event_tags,
            :loc_lat,
            :loc_long,
            :loc_name,
            :image_directory
            );"
        );
        $saveEventStatement->execute(array(
            ':user_id' => $user_id,
            ':package_id' => $package_id,
            ':event_name' => $event_name,
            ':event_date_from' => $event_date_from,
            ':event_date_to' => $event_date_to,
            ':event_description' => $event_description,
            ':event_tags' => $event_tags,
            ':loc_lat' => $loc_lat,
            ':loc_long' => $loc_long,
            ':loc_name' => $loc_name,
            ':image_directory' => $image_directory
        ));


        return $saveEventStatement;
    }

    function editEvent($event_id, $user_id, $package_id, $event_name, $event_date_from, $event_date_to, $event_description, $event_tags, $loc_lat, $loc_long, $loc_name, $image_directory)
    {


        $editEventStatement = $this->conn->prepare(
            "UPDATE `events` 
            SET 
            `user_id` = :user_id,
            `package_id` = :package_id,
            `event_name` = :event_name,
            `event_date_from` = :event_date_from,
            `event_date_to` = :event_date_to,
            `event_description` = :event_description,
            `event_tags` = :event_tags,
            `loc_lat` = :loc_lat,
            `loc_long` = :loc_long,
            `loc_name` = :loc_name,
            `image_directory` = :image_directory
             
             WHERE `event_id` = :event_id"
        );
        $editEventStatement->execute(array(
            ':user_id' => $user_id,
            ':package_id' => $package_id,
            ':event_name' => $event_name,
            ':event_date_from' => $event_date_from,
            ':event_date_to' => $event_date_to,
            ':event_description' => $event_description,
            ':event_tags' => $event_tags,
            ':event_id' => $event_id,
            ':loc_lat' => $loc_lat,
            ':loc_long' => $loc_long,
            ':loc_name' => $loc_name,
            ':image_directory' => $image_directory
        ));


        return $editEventStatement;
    }

    function deleteEvent($event_id)
    {
        $deleteEventStatement = $this->conn->prepare(
            "delete from events where event_id = :event_id"
        );
        $deleteEventStatement->execute(array(':event_id' => $event_id));


        return $deleteEventStatement;
    }

    function getAllUserEvents($user_id)
    {

        $selectAllUserEvent = $this->conn->prepare(
            "select * from events where user_id = :user_id"
        );
        $selectAllUserEvent->execute(array(':user_id' => $user_id));


        return $selectAllUserEvent;

    }

    //ITEMS CRUD

    function addItem($category_id, $item_name, $item_description)
    {


        $saveItemStatement = $this->conn->prepare(
            "INSERT INTO `items` (
                `item_id`, 
                `category_id`, 
                `item_name`, 
                `item_description`) 
            VALUES (
                NULL, 
                :category_id, 
                :item_name, 
                :item_description
                );"
        );
        $saveItemStatement->execute(array(
            ':category_id' => $category_id,
            ':item_name' => $item_name,
            ':item_description' => $item_description,
        ));


        return $saveItemStatement;
    }

    function editItem($item_id, $category_id, $item_name, $item_description)
    {


        $editItemStatement = $this->conn->prepare(
            "UPDATE `items` 
            SET 
            `category_id` = :category_id,
            `item_name` = :item_name,
            `item_description` = :item_description
             
             WHERE `item_id` = :item_id"
        );
        $editItemStatement->execute(array(
            ':category_id' => $category_id,
            ':item_name' => $item_name,
            ':item_description' => $item_description,
            ':item_id' => $item_id
        ));


        return $editItemStatement;
    }

    function deleteItem($item_id)
    {


        $deleteItemStatement = $this->conn->prepare(
            "DELETE FROM items WHERE item_id = :item_id"
        );
        $deleteItemStatement->execute(array(
            ':item_id' => $item_id
        ));


        return $deleteItemStatement;
    }

    function getItemsByCategory($category_id)
    {

        $getItemStatement = $this->conn->prepare(
            "Select * FROM items WHERE category_id = :category_id"
        );
        $getItemStatement->execute(array(
            ':category_id' => $category_id
        ));


        return $getItemStatement->FetchAll(PDO::FETCH_OBJ);
    }


}


?>