<!-- <footer id="footer" class="footer">
    <div class="copyright">
        Powered by <strong><span>SSS</span></strong> Copyright &copy; 2023
    </div>
</footer>End Footer -->

<li class="nav-heading li-footer" id="sidebar-title">
    <?php echo $dictionary->get_lang($lang,$KEY_POWERED_BY); ?> <strong><span>SSS</span></strong> <?php echo $dictionary->get_lang($lang,$KEY_COPYRIGHT); ?> &copy; 2023
</li><!-- End Footer -->

<style>
    .li-footer{
        position: absolute;
        bottom: 0px;
        color:#012970 !important;
        left: 14px;
        z-index: -1;
    }
</style>