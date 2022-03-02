<?php

class TaskGateway
{
    private PDO $conn;


    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }


    public function create(array $data)
    {
        for ($i = 0; $i <= 29; $i++) {
            $sql = "INSERT INTO property_data (County,Country,Town,DisplayableAddress,Latitude,Longitude,Number_of_Bedrooms,Number_of_Bathrooms,Price,ForSale_ForRent,Prop_Type)
                VALUES (:County,:Country,:Town,:DisplayableAddress,:Latitude,:Longitude,:Number_of_Bedrooms,:Number_of_Bathrooms,:Price,:ForSale_ForRent)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(":County", $data["data"][$i]["county"]);
            $stmt->bindParam(':Country', $data["data"][$i]["country"]);
            $stmt->bindParam(':Town', $data["data"][$i]["town"]);
            $stmt->bindParam(':DisplayableAddress', $data["data"][$i]["address"]);
            $stmt->bindParam(':Latitude', $data["data"][$i]["latitude"]);
            $stmt->bindParam(':Longitude', $data["data"][$i]["longitude"]);
            $stmt->bindParam(':Number_of_Bedrooms', $data["data"][$i]["num_bedrooms"]);
            $stmt->bindParam(':Number_of_Bathrooms', $data["data"][$i]["num_bathrooms"]);
            $stmt->bindParam(':Price', $data["data"][$i]["price"]);
            $stmt->bindParam(':ForSale_ForRent', $data["data"][$i]["type"]);
            $stmt->bindParam(':Prop_Type', $data["data"][$i]["property_type"]["title"]);
            $stmt->execute();

            $Property_id = $this->conn->lastInsertId();
            $sql2 = "INSERT INTO property_details (Property_type_id,Description,Property_type_description,Image,Thumbnail)
                VALUES (:Property_type_id,:Description,:Property_type_description,:Image,:Thumbnail)";

            $stmt2 = $this->conn->prepare($sql2);

            $stmt2->bindParam(":Property_type_id", $Property_id);
            $stmt2->bindParam(':Description', $data["data"][$i]["description"]);
            $stmt2->bindParam(':Property_type_description', $data["data"][0]["property_type"]["description"]);
            $stmt2->bindParam(':Image', $data["data"][$i]["image_full"]);
            $stmt2->bindParam(':Thumbnail', $data["data"][$i]["image_thumbnail"]);


            $stmt2->execute();
        }
    }

    public function search($searchvalues,)
    {
        $sql = 'SELECT County,Country,Town,DisplayableAddress,Latitude,Longitude,Number_of_Bedrooms,Number_of_Bathrooms,Price,ForSale_ForRent FROM property_data  WHERE 1 ';
        $where = '';
        $pdoData = [];


        foreach ($searchvalues as $key => $value) {
            if (!$value) {
                continue;
            }

            if ($key === 'Town') {
                $pdoData[':Town'] = $value;
                $where .= ' AND Town = :Town ';
            }
            if ($key === 'Number_of_Bedrooms') {
                echo "het";
                $pdoData[':Number_of_Bedrooms'] = (int)$value;
                $where .= ' AND Number_of_Bedrooms = :Number_of_Bedrooms ';
            }
            if ($key === 'Price') {
                $pdoData[':price'] = (int)$value;
                $where .= ' AND Price = :Price ';
            }

            if ($key === 'PropertyType') {
                $pdoData[':PropertyType'] = $value;
                $where .= ' AND Prop_Type = :PropertyType';
            }

            if ($key === 'For') {
                $pdoData[':For'] = $value;
                $where .= ' AND ForSale_ForRent = :For ';
            }
        }

        $sql = $sql . $where;
        $stmt3 = $this->conn->prepare($sql);

        $stmt3->execute($pdoData);
        $results = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        return ($results);
    }

    public function getAll()
    {
        $sql = ' SELECT *FROM property_data';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ($results);
    }


    public function search_limit($initial_page, $limit, $searchvalues)
    {
        $sql = 'SELECT County,Country,Town,DisplayableAddress,Latitude,Longitude,Number_of_Bedrooms,Number_of_Bathrooms,Price,ForSale_ForRent FROM property_data  WHERE 1 ';
        $where = '';
        $limit = 'LIMIT ' . $initial_page . ',' . $limit;
        $pdoData = [];


        foreach ($searchvalues as $key => $value) {
            if (!$value) {
                continue;
            }

            if ($key === 'Town') {
                $pdoData[':Town'] = $value;
                $where .= ' AND Town = :Town ';
            }
            if ($key === 'Number_of_Bedrooms') {
                echo "het";
                $pdoData[':Number_of_Bedrooms'] = (int)$value;
                $where .= ' AND Number_of_Bedrooms = :Number_of_Bedrooms ';
            }
            if ($key === 'Price') {
                $pdoData[':price'] = (int)$value;
                $where .= ' AND Price = :Price ';
            }

            if ($key === 'PropertyType') {
                $pdoData[':PropertyType'] = $value;
                $where .= ' AND Prop_Type = :PropertyType';
            }

            if ($key === 'For') {
                $pdoData[':For'] = $value;
                $where .= ' AND ForSale_ForRent = :For ';
            }
        }

        $sql = $sql . $where . $limit;
        $stmt3 = $this->conn->prepare($sql);

        $stmt3->execute($pdoData);
        $results = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        return ($results);
    }


    public function getLimitedData($initial_page, $limit)
    {
        $sql = 'SELECT *FROM property_data LIMIT ' . $initial_page . ',' . $limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ($results);
    }

}

