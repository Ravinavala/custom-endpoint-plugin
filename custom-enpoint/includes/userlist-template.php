<?php get_header(); ?>
<section class="main-content-section inspyde_users">
    <div class="user-heading-section">
        <div class="container">
            <h1>User list table</h1>
        </div>
    </div>
    <div class="inner-content-block">
        <div class="container">
            <div class="user-block">
                <div class="container" data-ng-app="myApp" data-ng-controller="myCtrl">
                    <div class="manage-user-list">
                        <table id="user-list">
                            <tbody><tr class="header">
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                </tr>
                                <tr class="user_row">
                                    <td><a href="javascript:void(0)>UserInfo</a></td>
                                    <td><a href="javascript:void(0)>Name</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="user_detail_block">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
