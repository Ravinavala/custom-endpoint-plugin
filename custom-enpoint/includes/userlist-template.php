<?php get_header(); ?>
<section class="main-content-section inspyde_users">
    <div class="user-heading-section">
        <div class="container">
            <h1>User list table</h1>
        </div>
    </div>
    
        <div class="container">
            <div class="user-block">
                <?php
                $CustomEndpoint = new Custom_Endpoint();
                $user_lists = $CustomEndpoint->getUsers();
                ?>
                <div class="container" data-ng-app="myApp" data-ng-controller="myCtrl">
                    <div class="manage-user-list">
                        <table id="user-list">
                            <tbody><tr class="header">
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                </tr>
                                <?php
                                if (!empty($user_lists)) {
                                    foreach ($user_lists as $user) {
                                        ?>
                                        <tr class="user_row">
                                            <td><a href="javascript:void(0);" data-userid="<?php echo $user['id']; ?>"><?php echo $user['id']; ?></a></td>
                                            <td><a href="javascript:void(0);" data-userid="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></a></td>
                                            <td><a href="javascript:void(0);" data-userid="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="user_detail_block">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php get_footer(); ?>
