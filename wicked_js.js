function randomStr()
{
    let possible = '*(_)ABCDstuEJ^KLMNOPT&UVWXYZabcdefghijklm#nopQRSqrvwxyz012{345}67FGHI89';
    let pass =  "";
    for(let i = 0; i < 16; i++) {
        pass += possible.charAt( Math.floor(Math.random() * possible.length));
    }
    return pass;
}

(
var c = document.getElementById("myCanvas");
var ctx = c.getContext("2d");
var scale = 10;

function star( x, y, r)
{
    if (r > 0) {
        star(x-scale*r, y+scale*r, Math.floor(r/2));
        star(x+scale*r, y+scale*r, Math.floor(r/2));
        star(x-scale*r, y-scale*r, Math.floor(r/2));
        star(x+scale*r, y-scale*r, Math.floor(r/2));
        ctx.beginPath();
        ctx.arc(x, y, scale*r, 0, 2 * Math.PI);
        ctx.stroke();
    }
}

star( 300, 300, 4);
)()
