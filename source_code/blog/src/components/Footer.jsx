import {FaFacebook, FaTwitter, FaInstagram} from 'react-icons/fa';

const Footer = () => {
    return (
        <footer>
            <div className="section-container">
                <div className="footer-contact">
                    <p>Contact: <a href="mailto:info@yourcompany.com">info@yourcompany.com</a></p>
                    <ul className="social-media">
                        <li><a href="#"><FaFacebook/></a></li>
                        <li><a href="#"><FaTwitter/></a></li>
                        <li><a href="#"><FaInstagram/></a></li>
                    </ul>
                </div>
                <p>&copy; 2023 Your Company Name. All rights reserved.</p>
            </div>
        </footer>
    );
};

export default Footer;
