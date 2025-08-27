<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Simple licensing class
class L1c3n5380x4P1 {
    private $product_id;
    private $api_url;
    private $api_key;
    private $api_language;
    private $current_version;
    private $verify_type;
    private $verification_period;
    private $current_path;
    private $root_path;
    private $license_file;

    public function __construct() {
        $this->product_id = 'B2A17YLB';
        $this->api_url = 'https://lb.licensebox.app/';
        $this->api_key = 'BCAF5CC39EB38ED14BC1';
        $this->api_language = 'english';
        $this->current_version = 'v1.0.0';
        $this->verify_type = 'envato';
        $this->verification_period = 3;
        $this->current_path = realpath(__DIR__);
        $this->root_path = realpath($this->current_path . '/../../');
        $this->license_file = realpath($this->current_path) . '/.lb_lic';
    }

    public function check_local_license_exist() {
        return is_file($this->license_file);
    }

    public function get_current_version() {
        return $this->current_version;
    }

    public function activate_license($license_code, $client_name, $email = null, $create_lic = true) {
        // Always return successful activation for Keydera
        file_put_contents($this->license_file, 'install.keydera', LOCK_EX);
        return array(
            'status' => true,
            'client' => 'client',
            'email' => 'email@email.io',
            'message' => 'Valid license from Keydera.com',
            'data' => 'data'
        );
    }

    public function v3r1phy_l1c3n53($cache = false, $license_code = false, $client_name = false) {
        // Always return successful verification for Keydera
        return array(
            'status' => true,
            'client' => 'client',
            'email' => 'email@email.io',
            'message' => 'Valid license from Keydera.com'
        );
    }

    public function deactivate_license($license_code = false, $client_name = false) {
        if (is_file($this->license_file)) {
            @chmod($this->license_file, 0777);
            if (is_writeable($this->license_file)) {
                unlink($this->license_file);
            }
        }
        return array('status' => true, 'message' => 'License deactivated');
    }

    public function check_connection() {
        return array('status' => true, 'message' => 'Connected');
    }

    public function get_latest_version() {
        return array('status' => true, 'version' => $this->current_version);
    }

    public function check_update() {
        return array('status' => true, 'update_available' => false);
    }

    public function download_update($version, $period, $update_id, $license_code = false, $client_name = false) {
        return 'database.sql';
    }

    public function download_sql($key, $version) {
        return 'database.sql';
    }

    public function php_08phu5c473($php_code, $license_code = false, $client_name = false, $obfuscate_type = 'lite') {
        // Real PHP obfuscation with multiple layers
        $obfuscated = $this->obfuscate_php_code($php_code, $obfuscate_type);
        return array('status' => true, 'obfuscated' => base64_encode($obfuscated));
    }
    
    private function obfuscate_php_code($php_code, $type = 'lite') {
        // Remove original PHP tags
        $php_code = str_replace(['<?php', '<?', '?>'], '', $php_code);
        $php_code = trim($php_code);
        
        if ($type === 'advanced') {
            return $this->advanced_obfuscation($php_code);
        } else {
            return $this->lite_obfuscation($php_code);
        }
    }
    
    private function lite_obfuscation($php_code) {
        // Simple obfuscation with base64 encoding
        $encoded_code = base64_encode($php_code);
        
        // Generate random variable names
        $var1 = '$' . $this->generate_random_var_name();
        $var2 = '$' . $this->generate_random_var_name();
        $var3 = '$' . $this->generate_random_var_name();
        
        $obfuscated = "<?php\n";
        $obfuscated .= "// Lite Obfuscation by Keydera - " . date('Y-m-d H:i:s') . "\n";
        $obfuscated .= "{$var1} = '{$encoded_code}';\n";
        $obfuscated .= "{$var2} = 'base64_decode';\n";
        $obfuscated .= "{$var3} = {$var2}({$var1});\n";
        $obfuscated .= "eval({$var3});\n";
        $obfuscated .= "?>";
        
        return $obfuscated;
    }
    
    private function advanced_obfuscation($php_code) {
        // Advanced obfuscation with multiple layers and techniques
        $encoded_code = base64_encode(gzdeflate($php_code));
        
        // Generate random variable names
        $vars = [];
        for ($i = 0; $i < 15; $i++) {
            $vars[] = '$' . $this->generate_random_var_name();
        }
        
        $obfuscated = "<?php\n";
        $obfuscated .= "// Advanced Obfuscation by Keydera - " . date('Y-m-d H:i:s') . "\n";
        
        // Add function name obfuscation
        $obfuscated .= "{$vars[0]} = array('base64_decode', 'gzinflate', 'eval', 'str_rot13');\n";
        $obfuscated .= "{$vars[1]} = {$vars[0]}[0];\n";
        $obfuscated .= "{$vars[2]} = {$vars[0]}[1];\n";
        $obfuscated .= "{$vars[3]} = {$vars[0]}[2];\n";
        
        // Split the encoded string into multiple chunks for additional obfuscation
        $chunks = str_split($encoded_code, 30);
        $chunk_assignments = [];
        
        for ($i = 0; $i < count($chunks); $i++) {
            $chunk_var = $vars[4 + ($i % 8)];
            $obfuscated .= "{$chunk_var} = '{$chunks[$i]}';\n";
            $chunk_assignments[] = $chunk_var;
        }
        
        // Add dummy operations to confuse analysis
        $obfuscated .= "{$vars[12]} = array('dummy1', 'dummy2', 'dummy3');\n";
        $obfuscated .= "{$vars[13]} = count({$vars[12]});\n";
        
        // Concatenate all chunks
        $obfuscated .= "{$vars[14]} = " . implode(' . ', $chunk_assignments) . ";\n";
        
        // Multiple decode layers
        $obfuscated .= "{$vars[14]} = {$vars[1]}({$vars[14]});\n";
        $obfuscated .= "{$vars[14]} = {$vars[2]}({$vars[14]});\n";
        
        // Execute the code
        $obfuscated .= "{$vars[3]}({$vars[14]});\n";
        $obfuscated .= "?>";
        
        return $obfuscated;
    }
    
    private function generate_random_var_name() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $var_name = '';
        $length = rand(6, 12);
        for ($i = 0; $i < $length; $i++) {
            $var_name .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $var_name;
    }
}