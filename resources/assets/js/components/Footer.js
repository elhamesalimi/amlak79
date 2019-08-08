import React from 'react';
const Footer = () => (
    <section id="footer">

    <footer id="footer-wrapper" >
        <div id="footer-info" style={{textAlign: 'center'}}>
            <div className="row copy-right">
                <div className="col-sm-10 col-xs-12">

                    <address className="address">
                        <b>آدرس:</b>خیابان دانشگاه مدنی شرقی اول ثالث شمالی
                        <br/><br/>
                            <b>تلفن:</b> 09369439176 _ 02833682408
                    </address>
                    <p>
                        کلیه حقوق مادی و معنوی این وبسایت متعلق به گروه املاک 79 می باشد.
                    </p>

                </div>

                <div className="col-sm-2 col-xs-12">
                    <div className="social-network">
                        <a href={"https://telegram.me/amlak79"} target={"blank"}> <i className="fa fa-telegram fa-2x"></i></a>
                        <i className="fa fa-instagram fa-2x"></i>
                        <i className="fa fa-google-plus fa-2x"></i>
                    </div>
                </div>
            </div>


        </div>
    </footer>
    </section>
);

export default Footer;