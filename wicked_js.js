function randomStr()
{
    let possible = '*(_)ABCDstuEJ^KLMNOPT&UVWXYZabcdefghijklm#nopQRSqrvwxyz012{345}67FGHI89';
    let pass =  "";
    for(let i = 0; i < 16; i++) {
        pass += possible.charAt( Math.floor(Math.random() * possible.length));
    }
    return pass;
}
