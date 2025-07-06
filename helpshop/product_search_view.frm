TYPE=VIEW
query=select `p`.`name` AS `product_name`,`poc`.`combination_key` AS `combination_key`,`poc`.`cost_price` AS `cost_price`,`poc`.`stock` AS `stock`,`pomp`.`price` AS `price`,`pomp`.`market_ix` AS `market_ix`,`m`.`market_name` AS `market_name`,`p`.`user_ix` AS `product_user_ix`,`m`.`user_ix` AS `market_user_ix` from (((`helpshop`.`product` `p` join `helpshop`.`product_option_combination` `poc` on(`p`.`ix` = `poc`.`product_ix`)) join `helpshop`.`product_option_market_price` `pomp` on(`poc`.`ix` = `pomp`.`product_option_comb_ix`)) join `helpshop`.`market` `m` on(`m`.`ix` = `pomp`.`market_ix`))
md5=30594941be7266b6a9618bcd24942e3e
updatable=1
algorithm=0
definer_user=root
definer_host=localhost
suid=2
with_check_option=0
timestamp=0001738978442315499
create-version=2
source=SELECT\np.name AS product_name,\npoc.combination_key,\npoc.cost_price,\npoc.stock,\npomp.price,\npomp.market_ix,\nm.market_name,\np.user_ix AS product_user_ix,\nm.user_ix AS market_user_ix\nFROM\nproduct p\nINNER JOIN\nproduct_option_combination poc\nON\np.ix = poc.product_ix\nJOIN\nproduct_option_market_price pomp\nON\npoc.ix = pomp.product_option_comb_ix\nJOIN\nmarket m\nON\nm.ix = pomp.market_ix
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `p`.`name` AS `product_name`,`poc`.`combination_key` AS `combination_key`,`poc`.`cost_price` AS `cost_price`,`poc`.`stock` AS `stock`,`pomp`.`price` AS `price`,`pomp`.`market_ix` AS `market_ix`,`m`.`market_name` AS `market_name`,`p`.`user_ix` AS `product_user_ix`,`m`.`user_ix` AS `market_user_ix` from (((`helpshop`.`product` `p` join `helpshop`.`product_option_combination` `poc` on(`p`.`ix` = `poc`.`product_ix`)) join `helpshop`.`product_option_market_price` `pomp` on(`poc`.`ix` = `pomp`.`product_option_comb_ix`)) join `helpshop`.`market` `m` on(`m`.`ix` = `pomp`.`market_ix`))
mariadb-version=100432
