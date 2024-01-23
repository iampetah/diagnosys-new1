<?php
require_once 'Database.php';
require_once __DIR__ . '/../Objects/Services.php';
class ServicesModel extends Database
{

    public function getAllServices()
    {
        $this->checkConnection();
        $sql = 'SELECT * FROM services';
        $result = $this->connection->query($sql);
        $services = array();
        while ($row = $result->fetch_assoc()) {
            $service = new Services();
            $service->id = $row['id'];
            $service->name = $row['name'];
            $service->price = $row['price'];
            $service->normal_value = $row['normal_value'];
            $services[] = $service;
        }
        $this->close();
        return $services;
    }
    public function getServicesByRequestId($id)
    {
        $this->checkConnection();
        $sql = 'SELECT
                services.id AS service_id,
                services.name AS service_name,
                services.price,
                services.normal_value,
                request_services.test,
                request_services.result
                
            FROM
                request_services
            JOIN
                services ON services.id = request_services.service_id
            WHERE
                request_services.request_id =  ?';

        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $id);

        $services = array();
        if ($statement->execute()) {
            $result = $statement->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $this->close();
            foreach ($data as $d) {
                $service = new Services();
                $service->name = $d['service_name'];
                $service->id = $d['service_id'];
                $service->price = $d['price'];
                $service->result = $d['result'];
                $service->test = $d['test'];
                $service->normal_value = $d['normal_value'];
                $services[] = $service;
            }

            return $services;
        } else {


            return false;
        }
    }
    public function getServicesByAppointmentId($id)
    {
        $this->checkConnection();
        $sql = 'SELECT
                services.id AS service_id,
                services.name AS service_name,
                services.price,
                services.normal_value
            FROM
                services
            JOIN
                appointment_services ON services.id = appointment_services.service_id
            WHERE
                appointment_services.appointment_id = ?';

        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $id);

        if ($statement->execute()) {
            $result = $statement->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $services = array();
            foreach ($data as $d) {
                $service = new Services();
                $service->name = $d['service_name'];
                $service->id = $d['service_id'];
                $service->price = $d['price'];
                $service->normal_value = $d['normal_value'];
                $services[] = $service;
            }

            return $services;
        } else {
            // Handle the case where the query execution fails
            return false;
        }
    }
    function getServiceSales()
    {
        $this->checkConnection();
        $sql = "SELECT s.name AS name, SUM(s.price) AS price FROM `request_services` rs JOIN `services` s ON rs.service_id = s.id GROUP BY s.name;";
        $statement = $this->connection->prepare($sql);

        if ($statement->execute()) {
            $result = $statement->get_result();

            // Fetch all rows as objects
            $data = [];
            while ($row = $result->fetch_object('Services')) {
                $data[] = $row;
            }
            $this->close();
            return $data;
        }
    }
    function getServicesByName($serviceName)
    {
        $sql = "SELECT * FROM `services` WHERE `name` = ?";
        $statement = $this->connection->prepare($sql);

        // Bind the parameter
        $statement->bind_param("s", $serviceName);

        if ($statement->execute()) {
            $result = $statement->get_result();

            // Fetch all rows as objects
            $data = $result->fetch_object('Services');

            return $data;
        }
    }
    function getServicesByDateAndName()
    {
        $this->checkConnection();
        $sql = "SELECT
                    r.request_date AS date,
                    s.name AS name,
                    s.price AS price,
                    COUNT(*) AS service_count
                FROM
                    `request_services` rs
                JOIN
                    `request` r ON rs.request_id = r.id
                JOIN
                    `services` s ON rs.service_id = s.id
                GROUP BY
                    r.request_date, s.name
                ORDER BY
                    r.request_date DESC;";

        $statement = $this->connection->prepare($sql);

        if ($statement->execute()) {
            $result = $statement->get_result();

            // Fetch all rows as objects
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;
        }
    }



    function addService($service)
    {
        $sql = "INSERT INTO `services` (`name`, `price`, `normal_value`) VALUES (?, ?, ?)";
        $statement = $this->connection->prepare($sql);

        // Bind the parameters
        $statement->bind_param("sds", $service->name, $service->price, $service->normal_value);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function editServicesById(Services $service)
    {
        $sql = "UPDATE `services` SET `name` = ?, `normal_value` = ?, `price` = ? WHERE `id` = ?";
        $statement = $this->connection->prepare($sql);

        // Bind the parameters
        $statement->bind_param("ssii", $service->name, $service->normal_value, $service->price, $service->id);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }
    function deleteServiceById($id)
    {
        $sql = "DELETE FROM `services` WHERE `id` = ?";
        $statement = $this->connection->prepare($sql);

        // Bind the parameter
        $statement->bind_param("i", $id);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function addPackageServices($serviceIds, $packageName)
    {
        $this->checkConnection();

        try {
            // Start a transaction
            $this->connection->begin_transaction();

            // Insert the package
            $sql = "INSERT INTO package (package_name) VALUES (?)";
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("s", $packageName);
            $statement->execute();

            // Get the package ID
            $packageId = $this->connection->insert_id;

            // Insert mappings for each service ID
            foreach ($serviceIds as $serviceId) {
                $sql = "INSERT INTO package_services (package_id, service_id) VALUES (?, ?)";
                $statement = $this->connection->prepare($sql);
                $statement->bind_param("ii", $packageId, $serviceId);
                $statement->execute();
            }

            // Commit the transaction
            $this->connection->commit();

            return true;
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->connection->rollback();
            throw $e;
        }
    }
    public function getAllPackages()
    {
        $this->checkConnection();

        $sql = "SELECT p.id AS package_id, p.package_name, GROUP_CONCAT(s.id) AS service_ids
            FROM package p
            JOIN package_services ps ON p.id = ps.package_id
            JOIN services s ON ps.service_id = s.id
            GROUP BY p.id";

        $result = $this->connection->query($sql);
        $packages = array();

        while ($row = $result->fetch_assoc()) {
            $package = array();
            $package['id'] = $row['package_id'];
            $package['name'] = $row['package_name'];
            $package['service_ids'] = explode(',', $row['service_ids']); // Convert string of IDs to array
            $packages[] = $package;
        }

        $this->close();
        return $packages;
    }
}
