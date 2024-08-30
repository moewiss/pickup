<?php

add_filter( 'woocommerce_form_field_heading','sc_woocommerce_form_field_heading', 10, 4 );
function sc_woocommerce_form_field_heading($field, $key, $args, $value) {
    $output = '<h3 class="form-row form-row-wide">'.__( $args['label'], 'woocommerce' ).'</h3>';
    echo $output;
}


function get_checkout_product_categories() {
    // Get the current WooCommerce checkout instance
    $checkout = WC()->cart;

    // Initialize an array to store unique product category slugs
    $category_slugs = array();

    // Loop through each cart item
    foreach ($checkout->get_cart() as $cart_item_key => $cart_item) {
        do_action( 'woocommerce/cart_loop/start', $cart_item );
        // Get the product ID from the cart item data
        $product_id = $cart_item['product_id'];

        // Get the product categories for the current product
        $product_categories = get_the_terms($product_id, 'product_cat');
        // Check if product has categories
        if ($product_categories && !is_wp_error($product_categories)) {
            // Loop through each category and add the slug to the array
            foreach ($product_categories as $category) {
                $category_slugs[] = $category->slug;

                // Check if the attribute "subcategory," "product category," "item category," or "product group" contains the value "panties"
                $product_attributes = wc_get_product($product_id)->get_attributes();
                foreach ($product_attributes as $attribute) {
                    if (in_array('panties', $attribute->get_options())) {
                        $category_slugs[] = 'panties';
                        break;
                    }
                }
            }
        }
        do_action( 'woocommerce/cart_loop/end', $cart_item );
    }

    // Remove duplicates from the array
    $category_slugs = array_unique($category_slugs);

    // Return the list of category slugs
    return $category_slugs;
}




function get_checkout_product_brand_name() {
    // Get the current WooCommerce checkout instance
    $checkout = WC()->cart;

    // Initialize an array to store unique product category slugs
    $brand_name_slugs = array();

    // Loop through each cart item
    foreach ($checkout->get_cart() as $cart_item_key => $cart_item) {
        do_action( 'woocommerce/cart_loop/start', $cart_item );
        // Get the product ID from the cart item data
        $product_id = $cart_item['product_id'];
        
        // Get the product object
        $product = wc_get_product($product_id);
        // Assuming 'pa_brand' is the attribute you want to retrieve
        $attribute_name = 'pa_brand';
        // Get the attribute value
        $product_brand_names[] = strtolower($product->get_attribute($attribute_name));
        
        do_action( 'woocommerce/cart_loop/end', $cart_item );
    }

    // Remove duplicates from the array
    $product_brand_names = array_unique($product_brand_names);

    // Return the list of category slugs
    return $product_brand_names;
}


function display_blog_name_per_product() {
    // Get the cart instance
    global $woocommerce, $wpdb;
    $cart = $woocommerce->cart;
    $blognames = array();

    // Loop through cart items
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        do_action( 'woocommerce/cart_loop/start', $cart_item );
        // Get the product ID for the item
        $product_id = $cart_item['product_id'];

        // Get the product object
        $product = wc_get_product( $product_id );

        // Get the blog ID for the product
        $blog_id = get_post_meta( $product_id, '_wpmn_blog_id', true );

        // Get the blog details based on the blog ID
        $blog_details = get_blog_details( $blog_id );
        $blog_name = $blog_details->blogname;
        $blognames[] = strtolower($blog_name);
        do_action( 'woocommerce/cart_loop/end', $cart_item );
    }
    return $blognames;
}


function print_checkout_product_brand_names() {
    $categorynames = get_checkout_product_categories();
    $uniquecategorynames = array_unique($categorynames);
    //echo '<p><strong>Categories:</strong> ' . implode(', ', $uniquecategorynames) . '</p>';

    $brandnames = get_checkout_product_brand_name();
    $uniquebrandnames = array_unique($brandnames);
    //echo '<p><strong>Brands:</strong> ' . implode(', ', $uniquebrandnames) . '</p>';

    $blognames = display_blog_name_per_product();
    $uniqueBlogNames = array_unique($blognames);
    //echo '<p><strong>Stores:</strong> ' . implode(', ', $uniqueBlogNames) . '</p>';

    if (!empty($uniqueBlogNames) || !empty($uniquebrandnames) ) {
        if (count($uniqueBlogNames) > 1) {
            echo '<style>li:has(input#shipping_method_0_local_pickup3) { display: none!important; }</style>';
        }
    }

    if (in_array('undiz', $uniquebrandnames) || in_array('undiz', $uniqueBlogNames) ) {
        echo'
        <script>
        document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
            var dropdown = document.getElementById("pickup_store");

                // Loop through each option in the dropdown
            for (var i = 0; i < dropdown.options.length; i++) {
                if (dropdown.options[i].text.toLowerCase().includes("city center")) {
                    dropdown.options[i].selected = true;
                    } else {
                        // Hide the other options
                        dropdown.options[i].style.display = "none";
                    }
                }
                });
                </script>';
            }
            elseif (in_array('culti', $uniquebrandnames) && in_array('culti milano', $uniqueBlogNames) ) {
                echo'<script>
                document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                    var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                    var optionsToCheck = ["abc achrafieh", "abc verdun"];

                // Loop through each option in the dropdown
                    for (var i = 0; i < dropdown.options.length; i++) {
                        var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                        if (optionsToCheck.some(option => optionText.includes(option))) {
                            dropdown.options[i].selected = true;
                            } else {
                        // Hide the other options
                                dropdown.options[i].style.display = "none";
                            }
                        }
                        });
                        </script>';
                    }
                    elseif (in_array('etam', $uniquebrandnames) || in_array('etam', $uniqueBlogNames) ) {
                        echo'<script>
                        document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                            var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                            var optionsToCheck = ["abc achrafieh", "city center"];

                // Loop through each option in the dropdown
                            for (var i = 0; i < dropdown.options.length; i++) {
                                var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                if (optionsToCheck.some(option => optionText.includes(option))) {
                                    dropdown.options[i].selected = true;
                                    } else {
                        // Hide the other options
                                        dropdown.options[i].style.display = "none";
                                    }
                                }
                                });
                                </script>';
                            }
                            elseif (in_array('jott', $uniquebrandnames) || in_array('michael kors', $uniquebrandnames) 
                                || in_array('ralph lauren men', $uniquebrandnames) ) {
                                echo'<script>
                            document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                var optionsToCheck = ["abc achrafieh", "abc dbayeh", "abc verdun"];

                // Loop through each option in the dropdown
                                for (var i = 0; i < dropdown.options.length; i++) {
                                    var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                    if (optionsToCheck.some(option => optionText.includes(option))) {
                                        dropdown.options[i].selected = true;
                                        } else {
                        // Hide the other options
                                            dropdown.options[i].style.display = "none";
                                        }
                                    }
                                    });
                                    </script>';
                                }
                                elseif (in_array('loccitane en provence', $uniqueBlogNames) ) {
                                    echo'<script>
                                    document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                        var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                        var optionsToCheck = ["abc achrafieh", "abc dbayeh", "abc verdun"];

                // Loop through each option in the dropdown
                                        for (var i = 0; i < dropdown.options.length; i++) {
                                            var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                            if (optionsToCheck.some(option => optionText.includes(option))) {
                                                dropdown.options[i].selected = true;
                                                } else {
                        // Hide the other options
                                                    dropdown.options[i].style.display = "none";
                                                }
                                            }
                                            });
                                            </script>';
                                            return;
                                        }
                                        elseif (in_array('mayoral', $uniquebrandnames) || in_array('mayoral', $uniqueBlogNames) ) {
                                            echo'<script>
                                            document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                                var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                                var optionsToCheck = ["city center"];

                // Loop through each option in the dropdown
                                                for (var i = 0; i < dropdown.options.length; i++) {
                                                    var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                                    if (optionsToCheck.some(option => optionText.includes(option))) {
                                                        dropdown.options[i].selected = true;
                                                        } else {
                        // Hide the other options
                                                            dropdown.options[i].style.display = "none";
                                                        }
                                                    }
                                                    });
                                                    </script>';
                                                }
                                                elseif (in_array('mc2 saint barth', $uniquebrandnames) ) {
                                                    echo'<script>
                                                    document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                                        var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                                        var optionsToCheck = ["abc achrafieh", "abc dbayeh", "abc verdun"];

                // Loop through each option in the dropdown
                                                        for (var i = 0; i < dropdown.options.length; i++) {
                                                            var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                                            if (optionsToCheck.some(option => optionText.includes(option))) {
                                                                dropdown.options[i].selected = true;
                                                                } else {
                        // Hide the other options
                                                                    dropdown.options[i].style.display = "none";
                                                                }
                                                            }
                                                            });
                                                            </script>';
                                                        }
                                                        elseif (in_array('yves rocher', $uniquebrandnames) && in_array('yves rocher', $uniqueBlogNames) ) {
                                                            echo'<script>
                                                            document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                                                var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                                                var optionsToCheck = ["abc achrafieh", "abc dbayeh", "abc verdun", "city center", "citymall", "le mall dbayeh"];

                // Loop through each option in the dropdown
                                                                for (var i = 0; i < dropdown.options.length; i++) {
                                                                    var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                                                    if (optionsToCheck.some(option => optionText.includes(option))) {
                                                                        dropdown.options[i].selected = true;
                                                                        } else {
                        // Hide the other options
                                                                            dropdown.options[i].style.display = "none";
                                                                        }
                                                                    }
                                                                    });
                                                                    </script>';
                                                                }
                                                                elseif (in_array('zahar', $uniqueBlogNames) || 
                                                                    in_array('billieblush', $uniquebrandnames) ||
                                                                    in_array('biomecanics', $uniquebrandnames) ||
                                                                    in_array('dkny', $uniquebrandnames) ||
                                                                    in_array('hugo', $uniquebrandnames) ||
                                                                    in_array('karl lagerfeld', $uniquebrandnames) ||
                                                                    in_array('kenzo', $uniquebrandnames) ||
                                                                    in_array('mayoral', $uniquebrandnames) ||
                                                                    in_array('michael kors kids', $uniquebrandnames) ||
                                                                    in_array('moschino', $uniquebrandnames) ||
                                                                    in_array('ralph lauren kids', $uniquebrandnames) ||
                                                                    in_array('zadig et voltaire', $uniquebrandnames)) {
                                                                    echo'<script>
                                                                    document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                                                        var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                                                        var optionsToCheck = ["abc achrafieh", "abc dbayeh", "abc verdun", "beirut souks", "zahar tripoli"];

                // Loop through each option in the dropdown
                                                                        for (var i = 0; i < dropdown.options.length; i++) {
                                                                            var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                                                            if (optionsToCheck.some(option => optionText.includes(option))) {
                                                                                dropdown.options[i].selected = true;
                                                                                } else {
                        // Hide the other options
                                                                                    dropdown.options[i].style.display = "none";
                                                                                }
                                                                            }
                                                                            });
                                                                            </script>';
                                                                        }
                                                                        elseif (in_array('kids around', $uniqueBlogNames) || 
                                                                            in_array('balmain', $uniquebrandnames) ||
                                                                            in_array('boss', $uniquebrandnames) ||
                                                                            in_array('dkny', $uniquebrandnames) ||
                                                                            in_array('givenchy', $uniquebrandnames) ||
                                                                            in_array('karl lagerfeld', $uniquebrandnames) ||
                                                                            in_array('kenzo', $uniquebrandnames) ||
                                                                            in_array('lanvin', $uniquebrandnames) ||
                                                                            in_array('marc jacobs', $uniquebrandnames) ||
                                                                            in_array('michael kors cwf', $uniquebrandnames) ||
                                                                            in_array('michael kors kids', $uniquebrandnames) ||
                                                                            in_array('sonia rykiel', $uniquebrandnames) ||
                                                                            in_array('the marc jacobs', $uniquebrandnames)) {
                                                                            echo'<script>
                                                                            document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
                                                                                var dropdown = document.getElementById("pickup_store");

                // Array of options to check
                                                                                var optionsToCheck = ["beirut souks", "abc verdun"];

                // Loop through each option in the dropdown
                                                                                for (var i = 0; i < dropdown.options.length; i++) {
                                                                                    var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
                                                                                    if (optionsToCheck.some(option => optionText.includes(option))) {
                                                                                        dropdown.options[i].selected = true;
                                                                                        } else {
                        // Hide the other options
                                                                                            dropdown.options[i].style.display = "none";
                                                                                        }
                                                                                    }
                                                                                    });
                                                                                    </script>';
                                                                                }
                                                                                elseif (in_array('abercrombrie&fitch', $uniquebrandnames) ||
                                                                                    in_array('adidas', $uniquebrandnames) ||
                                                                                    in_array('agent provocateur', $uniquebrandnames) ||
                                                                                    in_array('aigner', $uniquebrandnames) ||
                                                                                    in_array('alexandre j', $uniquebrandnames) ||
                                                                                    in_array('anastasia beverly hills', $uniquebrandnames) ||
                                                                                    in_array('antonio banderas', $uniquebrandnames) ||
                                                                                    in_array('arcancil', $uniquebrandnames) ||
                                                                                    in_array('armani', $uniquebrandnames) ||
                                                                                    in_array('aspel', $uniquebrandnames) ||
                                                                                    in_array('azzaro', $uniquebrandnames) ||
                                                                                    in_array('banana republic', $uniquebrandnames) ||
                                                                                    in_array('bassam fattouh', $uniquebrandnames) ||
                                                                                    in_array('bebe', $uniquebrandnames) ||
                                                                                    in_array('beesline', $uniquebrandnames) ||
                                                                                    in_array('benefit', $uniquebrandnames) ||
                                                                                    in_array('beverly hills polo club', $uniquebrandnames) ||
                                                                                    in_array('boss', $uniquebrandnames) ||
                                                                                    in_array('bourjois', $uniquebrandnames) ||
                                                                                    in_array('burberry', $uniquebrandnames) ||
                                                                                    in_array('bvlgari', $uniquebrandnames) ||
                                                                                    in_array('cacharel', $uniquebrandnames) ||
                                                                                    in_array('calvin klein', $uniquebrandnames) ||
                                                                                    in_array('carolina herrera', $uniquebrandnames) ||
                                                                                    in_array('cartier', $uniquebrandnames) ||
                                                                                    in_array('chanel', $uniquebrandnames) ||
                                                                                    in_array('chantal thomas', $uniquebrandnames) ||
                                                                                    in_array('che', $uniquebrandnames) ||
                                                                                    in_array('cherden denis group', $uniquebrandnames) ||
                                                                                    in_array('chloe', $uniquebrandnames) ||
                                                                                    in_array('chopard', $uniquebrandnames) ||
                                                                                    in_array('clarins', $uniquebrandnames) ||
                                                                                    in_array('clinique', $uniquebrandnames) ||
                                                                                    in_array('coach', $uniquebrandnames) ||
                                                                                    in_array('collistar', $uniquebrandnames) ||
                                                                                    in_array('creative beauty', $uniquebrandnames) ||
                                                                                    in_array('creed gulf beauty', $uniquebrandnames) ||
                                                                                    in_array('creed', $uniquebrandnames) ||
                                                                                    in_array('culti', $uniquebrandnames) ||
                                                                                    in_array('davidoff', $uniquebrandnames) ||
                                                                                    in_array('decleor', $uniquebrandnames) ||
                                                                                    in_array('diesel', $uniquebrandnames) ||
                                                                                    in_array('dior', $uniquebrandnames) ||
                                                                                    in_array('dolce & gabana', $uniquebrandnames) ||
                                                                                    in_array('dolce & gabbana', $uniquebrandnames) ||
                                                                                    in_array('dr sebagh', $uniquebrandnames) ||
                                                                                    in_array('dueto', $uniquebrandnames) ||
                                                                                    in_array('dunhill', $uniquebrandnames) ||
                                                                                    in_array('echantillion', $uniquebrandnames) ||
                                                                                    in_array('eco tools', $uniquebrandnames) ||
                                                                                    in_array('elie saab', $uniquebrandnames) ||
                                                                                    in_array('erborian', $uniquebrandnames) ||
                                                                                    in_array('escada', $uniquebrandnames) ||
                                                                                    in_array('essie', $uniquebrandnames) ||
                                                                                    in_array('estee lauder', $uniquebrandnames) ||
                                                                                    in_array('faces lebanon', $uniqueBlogNames) || 
                                                                                    in_array('farsali', $uniquebrandnames) ||
                                                                                    in_array('fendi', $uniquebrandnames) ||
                                                                                    in_array('ferrari', $uniquebrandnames) ||
                                                                                    in_array('freeman', $uniquebrandnames) ||
                                                                                    in_array('garnier', $uniquebrandnames) ||
                                                                                    in_array('gavia', $uniquebrandnames) ||
                                                                                    in_array('giorgio beverly hills', $uniquebrandnames) ||
                                                                                    in_array('givenchy', $uniquebrandnames) ||
                                                                                    in_array('gucci', $uniquebrandnames) ||
                                                                                    in_array('guerlain', $uniquebrandnames) ||
                                                                                    in_array('guess', $uniquebrandnames) ||
                                                                                    in_array('hackett', $uniquebrandnames) ||
                                                                                    in_array('hamlet', $uniquebrandnames) ||
                                                                                    in_array('hermes', $uniquebrandnames) ||
                                                                                    in_array('issey miyake', $uniquebrandnames) ||
                                                                                    in_array('james bond 007', $uniquebrandnames) ||
                                                                                    in_array('jean paul gaultier', $uniquebrandnames) ||
                                                                                    in_array('jimmy choo', $uniquebrandnames) ||
                                                                                    in_array('jp gaultier', $uniquebrandnames) ||
                                                                                    in_array('juicy couture', $uniquebrandnames) ||
                                                                                    in_array('juliette has a gun', $uniquebrandnames) ||
                                                                                    in_array('justin bieber', $uniquebrandnames) ||
                                                                                    in_array('kenzo', $uniquebrandnames) ||
                                                                                    in_array('kerastase', $uniquebrandnames) ||
                                                                                    in_array('kocostar', $uniquebrandnames) ||
                                                                                    in_array('l\'oréal paris', $uniquebrandnames) ||
                                                                                    in_array('l\'oréal professionel', $uniquebrandnames) ||
                                                                                    in_array('l\'oréal paris', $uniquebrandnames) ||
                                                                                    in_array('l\'oréal professionnel', $uniquebrandnames) ||
                                                                                    in_array('lacoste', $uniquebrandnames) ||
                                                                                    in_array('lady gaga', $uniquebrandnames) ||
                                                                                    in_array('lancaster', $uniquebrandnames) ||
                                                                                    in_array('lancôme', $uniquebrandnames) ||
                                                                                    in_array('lanvin', $uniquebrandnames) ||
                                                                                    in_array('le couvent de minime', $uniquebrandnames) ||
                                                                                    in_array('le joyau d\'olive', $uniquebrandnames) ||
                                                                                    in_array('lili', $uniquebrandnames) ||
                                                                                    in_array('loewe', $uniquebrandnames) ||
                                                                                    in_array('lysedia', $uniquebrandnames) ||
                                                                                    in_array('make up for ever', $uniquebrandnames) ||
                                                                                    in_array('make up forever', $uniquebrandnames) ||
                                                                                    in_array('mancera', $uniquebrandnames) ||
                                                                                    in_array('marc jacobs', $uniquebrandnames) ||
                                                                                    in_array('marco serussi', $uniquebrandnames) ||
                                                                                    in_array('max factor', $uniquebrandnames) ||
                                                                                    in_array('maybelline', $uniquebrandnames) ||
                                                                                    in_array('mercedes benz', $uniquebrandnames) ||
                                                                                    in_array('mercedes&benz', $uniquebrandnames) ||
                                                                                    in_array('meryem', $uniquebrandnames) ||
                                                                                    in_array('michael kors', $uniquebrandnames) ||
                                                                                    in_array('missoni', $uniquebrandnames) ||
                                                                                    in_array('miu miu', $uniquebrandnames) ||
                                                                                    in_array('molton brown', $uniquebrandnames) ||
                                                                                    in_array('mont blanc', $uniquebrandnames) ||
                                                                                    in_array('montale', $uniquebrandnames) ||
                                                                                    in_array('narciso rodriguez', $uniquebrandnames) ||
                                                                                    in_array('nina ricci', $uniquebrandnames) ||
                                                                                    in_array('nugg', $uniquebrandnames) ||
                                                                                    in_array('oils of nature', $uniquebrandnames) ||
                                                                                    in_array('opi', $uniquebrandnames) ||
                                                                                    in_array('paco rabanne', $uniquebrandnames) ||
                                                                                    in_array('pinky goat', $uniquebrandnames) ||
                                                                                    in_array('potion kitchen', $uniquebrandnames) ||
                                                                                    in_array('prada', $uniquebrandnames) ||
                                                                                    in_array('pupa milano ', $uniquebrandnames) ||
                                                                                    in_array('pupa milano', $uniquebrandnames) ||
                                                                                    in_array('pupa', $uniquebrandnames) ||
                                                                                    in_array('ragheb alama', $uniquebrandnames) ||
                                                                                    in_array('ralph lauren', $uniquebrandnames) ||
                                                                                    in_array('real techniques', $uniquebrandnames) ||
                                                                                    in_array('reem acra', $uniquebrandnames) ||
                                                                                    in_array('reload', $uniquebrandnames) ||
                                                                                    in_array('repetto', $uniquebrandnames) ||
                                                                                    in_array('revlon', $uniquebrandnames) ||
                                                                                    in_array('rimmel', $uniquebrandnames) ||
                                                                                    in_array('roberto cavalli', $uniquebrandnames) ||
                                                                                    in_array('rochas', $uniquebrandnames) ||
                                                                                    in_array('roja', $uniquebrandnames) ||
                                                                                    in_array('sally hansen', $uniquebrandnames) ||
                                                                                    in_array('salvatore ferragamo', $uniquebrandnames) ||
                                                                                    in_array('samer khouzami', $uniquebrandnames) ||
                                                                                    in_array('say hello to', $uniquebrandnames) ||
                                                                                    in_array('service', $uniquebrandnames) ||
                                                                                    in_array('shiseido', $uniquebrandnames) ||
                                                                                    in_array('sisley', $uniquebrandnames) ||
                                                                                    in_array('st dupont', $uniquebrandnames) ||
                                                                                    in_array('strivectin', $uniquebrandnames) ||
                                                                                    in_array('swarovski', $uniquebrandnames) ||
                                                                                    in_array('thierry mugler', $uniquebrandnames) ||
                                                                                    in_array('tiffany', $uniquebrandnames) ||
                                                                                    in_array('tl', $uniquebrandnames) ||
                                                                                    in_array('tom ford', $uniquebrandnames) ||
                                                                                    in_array('tos', $uniquebrandnames) ||
                                                                                    in_array('travalo', $uniquebrandnames) ||
                                                                                    in_array('ungaro', $uniquebrandnames) ||
                                                                                    in_array('valentino', $uniquebrandnames) ||
                                                                                    in_array('van cleef & arpels', $uniquebrandnames) ||
                                                                                    in_array('vera wang', $uniquebrandnames) ||
                                                                                    in_array('versace', $uniquebrandnames) ||
                                                                                    in_array('victoria secret', $uniquebrandnames) ||
                                                                                    in_array('viktor & rolf', $uniquebrandnames) ||
                                                                                    in_array('warlock', $uniquebrandnames) ||
                                                                                    in_array('wow beauty forward', $uniquebrandnames) ||
                                                                                    in_array('yves rocher', $uniquebrandnames) ||
                                                                                    in_array('yves saint laurent', $uniquebrandnames) ||
                                                                                    in_array('zein', $uniquebrandnames)) {
    echo'<script>
    document.addEventListener("DOMContentLoaded", function () {
                // Get the dropdown element
        var dropdown = document.getElementById("pickup_store");

                // Array of options to check
        var optionsToCheck = ["beirut souks", "city center", "le mall dbayeh"];

                // Loop through each option in the dropdown
        for (var i = 0; i < dropdown.options.length; i++) {
            var optionText = dropdown.options[i].text.toLowerCase();

                    // Check if the option text includes any of the specified options
            if (optionsToCheck.some(option => optionText.includes(option))) {
                dropdown.options[i].selected = true;
                } else {
                        // Hide the other options
                    dropdown.options[i].style.display = "none";
                }
            }
            });
            </script>';
        }
        if (in_array('pearl brands online', $uniqueBlogNames) && count($uniquebrandnames) > 1){
            echo '<style>li:has(input#shipping_method_0_local_pickup3) { display: none!important; }</style>';
        } 
    }

add_action('woocommerce_after_checkout_form', 'print_checkout_product_brand_names');


function hide_pickup_from_store() {

    if (!empty($uniqueBlogNames)) {
        if (count($uniqueBlogNames) > 1) {
            echo '<style>li:has(input#shipping_method_0_local_pickup3) { display: none!important; }</style>';
        }
    }
}

// Hook into the WooCommerce checkout page
//add_action('woocommerce_after_checkout_form', 'print_checkout_product_categories_and_hide_payment_gateway');

function print_checkout_product_categories_and_hide_payment_gateway() {
    // Get the unique product category slugs
        $category_slugs = get_checkout_product_categories();
        $blognames = display_blog_name_per_product();
    //echo '<p><strong>Product Categories:</strong> ' . implode(', ', $category_slugs) . '</p>';
    //echo '<p><strong>Blog Names:</strong> ' . implode(', ', $blognames) . '</p>';
    // Output the category slugs

        if (!empty($category_slugs) && !empty($blognames)) {

        // Check if 'beauty' or 'panties' is in the list of categories
            if (in_array('beauty', $category_slugs) || in_array('panties', $category_slugs) || in_array('lingerie', $category_slugs)
               || in_array('faces lebanon', $blognames)  || in_array('l\'occitane en provence', $blognames)  || in_array('culti milano', $blognames)  || in_array('yves rocher', $blognames) ) {
            // Hide the payment gateway with class 'payment_method_jetpack_custom_gateway'
                echo '<style>.payment_method_jetpack_custom_gateway { display: none!important; }</style>';
        }
    }
}
