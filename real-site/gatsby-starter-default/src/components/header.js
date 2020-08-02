import { Link } from "gatsby"
import PropTypes from "prop-types"
import React from "react"

const Header = ({ siteTitle }) => (
  <header>
  <div className="nav">
    <ul>
      <li>
        <a href="#home">Home</a>
      </li>
      <li>
        <a href="#portfolio">Portfolio</a>
      </li>
      <li>
        <a href="contact">Contact</a>
      </li>
    </ul>
  </div>
    <div className="box">
        <div className="viewport-hero"></div>
      <div className="tagline-box">
      <h1 className="tagline">
          {siteTitle}
      </h1>
      </div>
      </div>
  </header>
)

Header.propTypes = {
  siteTitle: PropTypes.string,
}

Header.defaultProps = {
  siteTitle: ``,
}

export default Header
