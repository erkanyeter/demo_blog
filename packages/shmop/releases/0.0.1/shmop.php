<?php

Class Shmop {

    public function get($storeKey)
    {
        $key    = crc32($storeKey);
        $shm_id = shmop_open($key, "a", 0644, 0); 

        if ($shm_id)
        {
            $size = shmop_size($shm_id);
            $data = shmop_read($shm_id, 0, $size); // Now lets read the string back

            if ( ! $data)
            {
                shmop_delete($shm_id);
                shmop_close($shm_id);
                
                return;
            }

            shmop_close($shm_id);

            return $data;
        }

        if( $shm_id != 0)
        {
            shmop_close($shm_id);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Write to memory
     * 
     * @param  string $storeKey 
     * @param  string $cacheData 
     * @return mixed           
     */
    public function set($storeKey, $data)
    {
        $key    = crc32($storeKey);
        $size   = mb_strlen($data, 'UTF-8');
        $shm_id = shmop_open($key, "c", 0755, $size);     // Create shared memory block with system id

        if ( ! $shm_id)
        {
            die("Couldn't create shared memory segment.");
        }

        $shmop_size = shmop_size($shm_id); // Get shared memory block's size
        $shm_bytes_written = shmop_write($shm_id, $data, 0);     // Lets write a test string into shared memory

        if ($shm_bytes_written != $size)
        {
            die("Couldn't write the entire length of data.");
        }

        shmop_close($shm_id);

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Delete the memory segment
     * 
     * @param  string $storeKey
     * @return void          
     */
    public function delete($storeKey)
    {
        $key    = crc32($storeKey);
        $shm_id = shmop_open($key, "a", 0644, 0); 

        shmop_delete($shm_id);
        shmop_close($shm_id);
    }

}

/*
$shmop = new Shmop;

$shmop->set('a', 'test');
$shmop->delete('a');
var_dump($shmop->get('a'));
*/






