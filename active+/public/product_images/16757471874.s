@charset "UTF-8";
  /*demo!XFyn6*/
/**
* Eric Meyer's Reset CSS v2.0
  /*demo!iqhi4zO*/
*/
  /*demo!twviJ8*/
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
  /*demo!PKz9Zx0mC*/
article, aside, canvas, details, embed,
figure, figcaption, footer, header,
  /*demo!bqm*/
menu, nav, output, ruby, section, summary,
time, mark, audio, video, input, textarea {
  margin: 0;
  padding: 0;
  border: 0;
  font: inherit;
  font-size: 100%;
  vertical-align: baseline;
}

/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure,
footer, header, menu, nav, section, time {
  display: block;
}
  /*demo!5JDqgwRdhTaTh4k*/

body {
  /*demo!B2gH2UX*/
  line-height: 1;
  /*demo!eveoC41eVaTAP6tg*/
  color: black;
  background: white;
}

a {
  color: inherit;
  text-decoration: none;
}

ol, ul {
  list-style: none;
}

blockquote, q {
  /*wkXd7*/
  quotes: none;
}
  /*ERgZ44T*/

blockquote:before, blockquote:after,
  /*qZ5CrmVdiqfAXuQ2J*/
q:before, q:after {
  content: '';
  content: none;
  /*demo!iykCTG6zVdH*/
}
  /*demo!O*/

  /*demo!2S149xw*/
table {
  border-collapse: collapse;
  border-spacing: 0;
}

/* meyerweb css reset end */

textarea:focus, input:focus {
  outline: 0;
  /*demo!wicAD5koI1ZKVP*/
}

input {
  /*pDg*/
  border-width: 0;
  /*nn1rggbNIuD3Xc3*/
}

em {
  font-style: italic;
}

h1, h2, h3, h4, h5, h6 {
  font-weight: bold;
  margin-top: 0;
  margin-bottom: 0;
}

.group:before,
.group:after {
  content: " ";
  /*demo!CIqxfZ*/
  display: table;
  /*4*/
}

.group:after {
  clear: both;
}

  /*demo!9q1mdUneGkHpUnAnv*/
.group {
  zoom: 1; /* ie 6/7 */
}

embed,
img,
object,
video {
  max-width: 100%;
}
sup {
  font-size: 58.3%;
  /*demo!*/
  vertical-align: text-top;
}
sub {
  /*demo!*/
  font-size: 58.3%;
  vertical-align: text-bottom;
}
.no-space-between-inline-blocks {
  /*demo!FW*/
  *letter-spacing: normal; /*reset IE < 8*/
  letter-spacing: -0.31em; /*webkit*/
  word-spacing: -0.43em; /*IE < 8 && gecko*/
}
/*restore spacing on inner elements*/
.no-space-between-inline-blocks > * {
  letter-spacing: normal;
  /*demo!E7hZFXpaOgmLeXG*/
  word-spacing: normal;
}
.displace {
  left: -5000px;
  position: absolute;
}
  /*xXCzFP*/
html {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
*,
  /*dDtifNh7OhKi3JBB*/
*:after,
  /*2FZ*/
*:before {
  -webkit-box-sizing: inherit;
  -moz-box-sizing: inherit;
  box-sizing: inherit;
}
html {
  font-size: 16px;
}
body {
  /*demo!uf2o7ijTrJi6E2guE*/
  min-width: 638px;
  color: #707070;
  /*demo!9r*/
  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
  font-size: 1.5625rem; /* 25px / 16px = 1.5625rem */
  font-style: normal;
  font-weight: normal;
  /*demo!I*/
  letter-spacing: 0;
  text-decoration: none;
  /*9QS4Xfu46Cr0Ko*/
}
.global_container_ {
  /*demo!bMNshJh87u76pSP*/
  float: none;
  height: auto;
  /*s0OaE0exAiu9SEISbH*/
  margin: 0 auto;
  padding: 0 0 26px;
  position: relative;
  width: 100%; /* 638px / 638px = 100% */
  /*demo!RnohEIjjUEYCt*/
  z-index: 0;
  /*juW5e5LbZN*/
  background: #ffffff center 0;
  background-position: center top;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  /*demo!KAgp4K*/
  background-size: cover;
}
.header {
  /*wJobb7TsDOvqWmL2*/
  height: 173px;
  margin: 0 auto;
  overflow: hidden;
  position: relative;
  width: 100%; /* 638px / 638px = 100% */
  z-index: 32;
}
.shape-3-copy-2-holder {
  left: 0;
  padding: 0 0 21px;
  position: absolute;
  /*demo!D*/
  top: 0;
  width: 100%; /* 638px / 638px = 100% */
  background: url(images/shape_3_copy_2.png) no-repeat center 0;
  background-position: center top;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  /*demo!K1n*/
  -o-background-size: cover;
  background-size: cover;
}
.shape-3-copy-3-holder {
  left: 103px;
  margin: 0 auto;
  /*H1SNStiNDOZK93*/
  position: relative;
  /*B*/
  width: 432px;
  background: url(images/shape_3_copy_3.png) no-repeat;
}
.shape-3-copy-4 {
  display: block;
  /*demo!DmFWLXK*/
  left: 78.5px;
  margin: 0 auto;
  position: relative;
}
.shape-3-copy-2 {
  left: 50%;
  position: absolute;
  /*WIM0ZEgCX*/
  top: 0;
  /*demo!lfjMOWwtJqH*/
  margin-left: -319px;
}
  /*xcI*/
.main-content-wrapper {
  margin: -16px auto 0;
  padding: 0 9px;
  position: relative;
  width: 638px;
  z-index: 15;
}
.row {
  margin: 0 0 0 48px;
  position: relative;
}
.address {
  float: left;
  margin: 11px 35px 0 0;
}
  /*demo!IPXexo*/
.text {
  float: left;
  /*demo!rZd*/
  color: #4c5f49;
  /*demo!UxNdj*/
  font-family: Montserrat, sans-serif;
  font-weight: 500;
  line-height: 33.60637px;
}
  /*demo!FQ1upJEcpWOtNnzb*/
.row-2 {
  margin: 31px 48px 0;
  /*ex53zW*/
  position: relative;
  /*rrlVi5*/
}
.text-2 {
  float: left;
  margin: 9px 34px 0 0;
}
.text-3 {
  /*demo!WUOOKOUNKaBc*/
  float: left;
  /*demo!gwzO*/
  color: #4c5f49;
  /*demo!t4pB*/
  font-family: Montserrat, sans-serif;
  font-size: 1.822917rem; /* 29.17px / 16px = 1.823125rem */
  font-weight: bold;
  line-height: 43.45833px;
  text-transform: uppercase;
}
  /*demo!nIWFJBxe*/
.instructions {
  display: block;
  margin: 39px auto 0;
  /*demo!HuySOyY*/
}
.text-4 {
  margin: 31px 0 0 31px;
  letter-spacing: 0.005em;
}
.text-5 {
  margin: 13px 0 0;
  letter-spacing: 0.005em;
  text-align: center;
}
.text-6 {
  margin: 9px 31px 0;
  letter-spacing: 0.005em;
  line-height: 1.2;
}
  /*YJSM*/
.rectangle-1-holder {
  height: 358px;
  margin: 24px auto 0;
  position: relative;
  /*hMZzgAzD2wH4XhvZdzg*/
  width: 582px;
  z-index: 2;
  -webkit-border-radius: 31px;
  -moz-border-radius: 31px;
  border-radius: 31px;
  background: #56b945;
}
.text-7 {
  left: 50%;
  /*UJXk*/
  position: absolute;
  top: 195px;
  color: #ffffff;
  /*demo!vZ*/
  font-family: Montserrat, sans-serif;
  font-size: 1.302083rem; /* 20.83px / 16px = 1.301875rem */
  line-height: 33.60637px;
  margin-left: -263px;
}
  /*demo!bFJsHMEKClx*/
.text-8 {
  left: 50%;
  /*demo!mYdIzdC4*/
  position: absolute;
  top: 33px;
  margin-left: -260px;
  /*demo!FWtQ3yjW*/
}
.text-9 {
  /*demo!P0rffRvsqL*/
  left: 50%;
  /*demo!RcIJluOZ*/
  position: absolute;
  top: 119px;
  margin-left: -262px;
}
.text-10 {
  left: 50%;
  position: absolute;
  top: 311px;
  margin-left: -262px;
}
.text-11 {
  /*M8ptI3r7qR*/
  left: 50%;
  position: absolute;
  top: 150px;
  color: #ffffff;
  font-size: 1.822917rem; /* 29.17px / 16px = 1.823125rem */
  font-weight: bold;
  line-height: 29px;
  text-align: center;
  /*demo!3Ng4x9yqjP0*/
  text-transform: uppercase;
  margin-left: -269px;
}
.wave-png-9943 {
  left: 50%;
  position: absolute;
  top: 29px;
  margin-left: -291px;
}
