/*
Version:	VDCS Common & Instantiation Library v2.0
		in jquery 1.10.2
Support:	http://uri.sx/vdcs.js
Uodated:	2014-04-00
*/

var VDCS={},dcs={ver:{bulid:'0.2.6.3',d:'20140400'}};
var d=document,dE=d.documentElement,dO=null,w=window;


eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('F 58=ac="\\n";3d.3R=B(){F 2n=3g[0]||{},i=1,al=3g.J,6w=V,6c;if(1v(2n)===\'9U\'){6w=2n;2n=3g[1]||{};i=2}if(1v(2n)!==\'4e\'&&!65(2n))2n={};if(al==i){2n=A;--i}P(;i<al;i++)if((6c=3g[i])!=12)P(F 1c in 6c){F 50=2n[1c],2J=6c[1c];if(2n===2J)fb;if(6w&&2J&&1v(2J)===\'4e\'&&!2J.fd)2n[1c]=3d.3R(6w,50||(2J.al!=12?[]:{}),2J);R if(2J!==2D)2n[1c]=2J}E 2n};3d.fg=B(o){E 3d.3R({},o)};3d.3p=3d.89=3d.ff=B(o,6M,15){$(o).3p(6M,15)};F fa={f9:B(){E B(){A.f4.f3(A,3g)}}};F f2={o:B(){F C,5c=3g.J;P(F i=0;i<5c;i++){F 15=3g[i];1G{C=15();L}1A(e){}};E C}};3R=B(o,a,b,c,d,e){E $.3R({},o,a,b,c,d,e)};6C=B(o,a,b,c,d,e){E 3d.3R(o,a,b,c,d,e)};$j=B(o,i){E 1b(o)?$(o):$((i?\'#\':\'\')+o)};$jo=B(o){o=$j(o);if(!o.J)o=12;E o};1m.d=1m.6x||B(){E T 1m().5X()};6C(1m.a5,{8L:{w:f5,d:f6,h:f8,i:ah,s:3f},6q:B(s){F d=T 1m();d.7v(1m.2S(s.1k(/-/gi,"/")));E d},f7:B(1L){1L=1L||\'9Y-a0-dd hh:ii:a1\';[\'9Y\',\'a0\',\'dd\',\'hh\',\'ii\',\'a1\'].5C(B(v,2w){1L=1L.1k(T 4N(v,\'g\'),\'$\'+(++2w))});E A.9W().1k(/^"(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)"$/g,1L).1k(/\\{\\d+\\}/,B(2w){2w=2w.7m(/\\{(\\d+)\\}/)[1];2w=2w.23(\'0\')==0?2w.1x(\'\')[1]:2w;2w=4d(2w)-1;E $v[\'1K.8B\'][2w]})},fh:B(){E $v[\'1K.a3\'][0]+$v[\'1K.a3\'][1].7o(A.fi())},3t:B(5R,95){E T 1m(A.5X()+95*A.8L[5R])},4y:B(5R,d){E 4d((d.5X()-A.5X())/A.8L[5R])},fs:B(d){F n=A.4y(\'s\',d==0?T 1m():$v.8A),C=[0,\'\',-1];if(n>a2){C[0]=3;C[2]=4d(n/a2);if(C[2]<1)C[2]=1}R if(n>9X){C[0]=2;C[2]=4d(n/9X);if(C[2]<1)C[2]=1}R if(n>60){C[0]=1;C[2]=4d(n/60);if(C[2]<1)C[2]=1}R if(n>0){C[0]=0;C[2]=n;if(C[2]<1)C[2]=1}if(C[2]>0)C[1]=r($v[\'1K.fr\'][C[0]],\'$1\',C[2]);E C},9W:B(){E\'"\'+A.7N()+\'-\'+(A.7X()+1).5D(2)+\'-\'+A.7I().5D(2)+\' \'+A.7g().5D(2)+\':\'+A.80().5D(2)+\':\'+A.7V().5D(2)+\'"\'}});9f=B(s){d.ay(s)};7M=B(s){E(s==12||s==2D)?K:V};ft=B(v){E(1v(v)==2D||1v(v)==\'2D\')?K:V};2i=B(s){E(s==12||s.J<1)?K:V};fu=B(s){E(s==K||s==1||s==\'a4\'||s==\'K\'||s==\'1\')?K:V};7x=B(s){E 1v(s)==\'4R\'};4M=B(o){F C=V;if(1b(o)){1G{if(o.J>-1)C=K}1A(e){}}E C};65=B(o){E o 9Q fw||1v(o)==\'B\'};fv=B(o){E o 9Q fp};1b=B(o){E(1v(o)==\'4e\'&&o)?K:V};fk=B(s){E 1v(s).2l()};7u=3P=B(s){E(4d(s)==s)};fj=7w=B(s){E(7y(s)==s)};fl=B(s){E/^[0-9]*[1-9][0-9]*$/.3V(s)?K:V};fm=fo=B(s,t,v){F 5E=(t==1)?\'9T.\':\'9T\',ar=T 26(1);if(2i(v)){ar[0]=V;ar[1]=K}R{ar[0]=0;ar[1]=s}if(2i(s))E ar[0];P(F i=0;i<s.J;i++){if(5E.23(s.7o(i))==-1)E ar[0]}E ar[1]};f1=B(o,t){E 1b(o)?o:$o(o,t)};f0=eE=B(s){E(s==K||s==1||s==\'a4\'||s==\'K\'||s==\'1\')};3e=eD=B(s,b){E 3P(s)?4d(s,b||10):0};6m=eG=B(s){E 7w(s,1)?7y(s):0};t=B(s){E 1v(s)==\'4R\'?s.eI():s};2h=B(s){E s.1k(/[^\\aF-\\aE]/g,\'aa\').J};r=B(s,1E,32){if(!s)E s;if(!2i(1E)){b5(s.23(1E)>-1){s=s.1k(1E,32)}};E s};eH=B(s,1E,32){E r(s,\'{\'+1E+\'}\',32)};1Z=B(s,1E,32){E r(s,\'{$\'+1E+\'}\',32)};eC=B(s,1E,32,ic){if(!4N.a5.ev(1E)){E s.1k(T 4N(1E,(ic?\'g\':\'gi\')),32)}R{E s.1k(1E,32)}};eu=es=5l=B(s,n){E s.3k(0,n)};ew=ey=7H=B(s,n){E s.3k(s.J-n)};aA=B(s,c){E s==2D?-1:s.23(c)};3z=B(s,v,p){p=p||\',\';E s==2D?-1:((p+s+p).23(p+v+p)+1)};eA=B(s,v,p){p=p||\',\';v=v.1x(\',\');F C=0;P(F a=0;a<v.J;a++){C=(p+s+p).23(p+v[a]+p)+1;if(C>0)L}E C};ez=B(o,2f,1w){2f=2f||\';\';1w=1w||\'=\';F C=\'\';if(1b(o)){P(F k in o){if(7x(o[k]))C+=2f+k+1w+o[k]}if(C)C=C.4L(2f.J)}E C};eJ=B(s,2f,1w){s=s||\'\';2f=2f||\';\';1w=1w||\'=\';F C={},ar,3n=s.1x(2f),5c=3n.J;P(F i=0;i<5c;i++){ar=3n[i].1x(1w);if(ar.J==2&&t(ar[0])!=\'\')C[t(ar[0])]=t(ar[1])}E C};2b=B(o,a,b,c,d,e){E $.3R({},o,a,b,c,d,e)};$v=1M.v={8A:T 1m(),\'1K.8B.eK\':[\'eV\',\'eU\',\'eW\',\'eX\',\'ag\',\'eZ\',\'eY\',\'eT\',\'eS\',\'eN\',\'eM\',\'eL\'],\'1K.8B.en\':[\'eP\',\'eR\',\'eQ\',\'fx\',\'ag\',\'gh\',\'gg\',\'gj\',\'gk\',\'gm\',\'gl\',\'gf\'],1O:{ge:\'元\',g9:\'元\',g8:\'元\',6D:\'金币\',6v:\'分\',6i:\'点\',g7:\'点\',ga:\'点\'}};$5e=$af=1M.af={};$3a=1M.3a={};$a={};$v.v=$5e.v=B(k,v){E 2i(v)?A[k]:A[k]=v};$v.ak=B(s){A.gb=s;A.8A=T 1m().6q(s)};F 36={71:\'\',id:-1,1c:\'\'};$c=1M.gc=1M.6J={4p:K,6g:\'gn\',Q:{\'U\':\'/\',\'1r\':\'/\',\'1r.3r\':\'3r/\',\'1r.ai\':\'3r/ai/\',\'1r.3M\':\'3r/3M/\',\'1r.aj\':\'3r/3M/aj/\',\'1r.6J\':\'6J/\',\'1r.ae\':\'ae/\',\'1r.9P\':\'9P/\',\'1r.a8\':\'a8/\',\'1r.a9\':\'a9/\',\'1r.5K.9K\':\'5K/\',\'1r.5K\':\'5K/3H/\'},25:\'\',p:\'\',m:\'\',2B:\'\',x:\'\',3F:\'\',1u:\'\',aM:\'gz-8\',gB:\'2K/1V\',gC:\'2K/X\',ac:\'\\n\',gE:\'ab\',gD:\'<3B 5x="gy">ab</3B>\',gx:\'[gr]\',6u:/\\{\\@([^{\\@}]*)}/gi,6h:/\\{\\$([^{\\$}]*)}/gi,4q:B(C,1I,3I,15){15=15||(4M(1I)?B(s,1E){E 1I[1E]}:B(s,1E){1G{E 1I?1I.3j(1E):\'\'}1A(ex){E 1I[1E]}});E C.1k(3I,15)},bC:B(C,1I){E A.4q(C,1I,A.6u)},8w:B(C,1I){E A.4q(C,1I,A.6h)},v:B(k,v){E v?$c.4E(k,v):$c.4z(k)},4z:B(k){E A.Q[k]},4E:B(k,v){A.Q[k]=v},aN:B(t){F C=A.4p?1m.d():1M.4Q.d;E t?(\'gq=\'+C):C},gs:B(6Y,8F,9B,4p){$v.ak(6Y);if(8F)1M.4Q.d=8F;A.9D=9B;A.8W=(A.9D==\'8W\');A.4p=(4p==\'l\'||4p==\'4p\')?K:V;A.9L()},9L:B(8X){if(8X)A.6g=8X;A.g6=A.8W?\'1V\':A.6g},g5:B(d,u,t){if(!2i(d))A.Q[\'1r\']=d;if(!2i(u))A.Q[\'U\']=u;if(!2i(t))A.Q[\'1r.fJ\']=(t==\'fI\')?A.Q[\'1r.5K.9K\']:t},fL:B(6D,6v,6i){6C($v.1O,{6D:6D,6v:6v,6i:6i})},fM:B(25,p,m,2B){A.25=25;A.p=p;A.m=m;A.2B=2B;if(1T.4m){1T.4m.bP(\'25\',A.25)}},94:B(){E{25:A.25,p:A.p,m:A.m,2B:A.2B,x:A.x,3F:A.3F,1u:A.1u}},fH:B(71,id,1c){if(1v 36==\'2D\')E;36.71=71;36.id=3e(id);if(1c)36.1c=1c;36.fG=A.U(\'3r\')+\'36/\'},fB:B(k,v){A.Q[\'1r.\'+k]=v},fA:B(k){E A.Q[\'1r.\'+k]},fz:B(k,v){A.Q[\'U.8V.\'+k]=v},U:B(k,f,a){F C=\'\';if(k)C=A.Q[\'1r.\'+k]?A.Q[\'1r.\'+k]:(A.Q[\'U.\'+k]?A.Q[\'U.\'+k]:\'\');if(C.4L(0,1)!=\'/\'&&C.23(\'://\')==-1)C=A.Q[\'U\']+C;if(f)C+=A.Q[\'U.8V.\'+f]?A.Q[\'U.8V.\'+f]:\'\';if(a)C=$U.2Q(C,a);E C},3v:B(k,f,a){E A.U(k,f,a)},\'\':\'\'};$b=1M.5j={fD:8Q,3K:8Q.fF.fE(),4Q:8Q.fP,b0:B(){F C={3K:A.3K,63:A.4Q.1x(\' \')[0],8S:/8S/.2v(A.3K),8P:/8P/.2v(A.3K),9I:/9H/.2v(A.3K),9F:/9F/.2v(A.3K),8R:/8R/.2v(A.3K),9G:!!w.9G,g0:(d.er==\'g1\')};C.v=3h.aX(C.63);C.9H=C.g2=C.9I;C.8P=65(w.g3);C.8R=65(w.fY);if(C.8S){F 8z=A.4Q.7m(/fX ([\\d]+)\\.([\\d]+);/);C.ie=K;C.5F=C.fS=8z[1];C.fR=8z[2];C.fT=C.5F<9;C.fU=(C.5F==6);C.fW=(C.5F==7);C.fV=(C.5F==8)}E C},3b:B(){F b=$b.5j=$b.b0();P(F k in b){$b[k]=b[k]}if(!$.5j)$.5j=$b.5j},aU:B(){if(A.b3)E;A.b3=K;A.N={};A.N[\'6e\']=d.6e;A.N[\'am\']=d.am;A.N[\'8h\']=w.6E.2l();F 8f=A.N[\'8h\'].1x(\'://\');A.N[\'dS\']=8f[0];A.N[\'aY\']=d.aY;A.N[\'25\']=\'\';A.N[\'aS\']=0;P(F a=0;a<5;a++){A.N[\'1r\'+(a+1)]=\'\'}F 2p=8f[1].1x(\'/\');if(2p.J>2){A.N[\'25\']=2p[1];A.N[\'aS\']=2p.J-2;P(F a=2;a<(2p.J-1);a++){A.N[\'1r\'+(a-1)]=2p[a]}A.N[\'3M\']=2p[2p.J-1]}R{A.N[\'3M\']=2p[1]}if(A.N[\'3M\'])A.N[\'1C\']=A.N[\'3M\'].3k(0,A.N[\'3M\'].dl(\'.\'))},3v:B(k){A.aU();E A.N[k||\'8h\']},U:B(k){E A.3v(k)},4c:B(k){E A.3v(k)},dB:B(){if(!A.8j){A.8j=A.8s()}E A.8j},8s:B(){F C=12;if(w.5v){F ar=T 26(\'c9.8e\',\'dv.8e\',\'dy.8e\');F 5c=ar.J;P(F i=0;i<5c;i++){1G{C=T 5v(ar[i])}1A(e){C=12}if(C!=12)L}}R if(w.b6){C=T b6()}E C}};$b.3b();$w=1M.cZ={w:-1,h:-1,88:-1,hs:-1,3c:-1,bf:-1,3b:B(){if(A.4s)E;A.4s=K;$(B(){$w.8c();$w.6B(B(){$w.3b()})})},8c:B(){F 3O=$(d);A.w=3O.22();A.h=3O.2m();A.bj=$(w).22();A.hi=$(w).2m();A.88=w.52.22-18;A.hs=d.2M.bi;if(A.3c<1)A.3c=$(\'#d0\').22();A.3c=A.3c||0;A.bf=(A.w-A.3c)/2},3y:B(){A.88=w.52.22-18;A.hs=d.2M.bi},89:B(t,15,o){3d.3p(o?o:w,t,15)},2G:B(15,o){A.89(\'2G\',15,o)},d9:B(15,o){$(o?o:w).3p(\'4A\',15);$(o?o:w).6B(15)},6B:B(f){A.bj=$(w).22();A.hi=$(w).2m();$(w).6B(f)},4A:B(f){$(w).4A(f)},d5:B(){F 19=-1,66=-1;if(w.dF){19=w.eq}R if(dE&&dE.8a){19=dE.8a}R if(d.2M){19=d.2M.8a}if(w.e5){66=w.eo}R if(dE&&dE.6r){66=dE.6r}R if(d.2M){66=d.2M.6r}E{x:19,y:66}},ej:B(s,p){E w.ei(s,p*3f)},bd:B(s){E bd(s)},5R:B(s,p){E w.dN(s,p*3f)},b8:B(s){E b8(s)}};$w.3b();$p=1M.1C={q:B(k){E $.59.4c(k)},aZ:B(k){E 3e(A.q(k))},go:B(s){d.6E.4x=s},dM:B(){w.4A(0,0);d.2M.6r=\'dH\'},8g:B(){w.dJ.c4()},6n:B(){E d.6E.dK()},9f:B(2e,U,6U){A.4F($U.2Q(U,\'d=\'+1M.4Q.d),12,6U)},4F:B(U,15,6U){$.4F(U,15)},47:B(2e,1j,G,15){if(!2e)E V;G=2b(G);if(1b(2e)){F 72=d.2M;if(1b(1j))72=1j;72.dR(t);E K}2T(2e){O\'o\':$(\'ba\').47(1j);L;O\'e\':$(\'2M\').47(1j);L;O\'js\':$.4F(1j,15);L;O\'4h\':$.4F(1j,15);L;O\'aV\':$(\'ba\').47(\'<54 2e="2K/4h">\'+1j+\'</54>\');L}},dX:B(s,u){if($b.ie)w.dT.e9(u,s);R if(w.bb)w.bb.e1(s,u,\'\');R 2X($5e[\'2L\'][\'e2.ee\'])},2J:B(s){$.2J(1b(s)?o.2C():s)},8r:B(u,n,3c,92,4A,8u){F aQ=(52.22)?(52.22-3c)/2:0,ax=(52.2m)?(52.ed-92)/2:0;F o=w.8r(u,n,\'22=\'+3c+\',2m=\'+92+\',5l=\'+aQ+\',9O=\'+ax+\',da=5U,6E=5U,cW=5U,1l=aT,cX=5U,cY=\'+4A+\',dx=5U\');if(8u){o.dC.ay(8u)}E o},ek:B(s,t){F C=\'\';if(3z(\'1,2\',t)<1)t=0;C=$5e[\'2L\'][\'bI\'][t];E w.dq(r(C,\'$1\',s))},6K:B(id,G){E $j(id?id:\'#6K\').6K(G)},dw:B(id,G){E A.6K(id,G)},\'\':\'\'};$f=1M.2j={as:\'[]\',2j:B(id,2P){F 1o=$(\'2j[1c="\'+id+\'"]\');if(1o.J<1)1o=$(\'2j#\'+id);if(2P&&1o&&1o.J<1)1o=12;E 1o},4V:B(id,2P){if(1b(id))E id;F 1o,5V=id;if(aA(id,\'.\')>0){F ar=id.1x(\'.\');5V=ar[1];F 8t=A.2j(ar[0],K);if(8t)1o=8t.2t(\':3i[1c="\'+5V+\'"]\')}R{1o=$(\':3i[1c="\'+5V+\'"]\')}if(1o&&1o.J<1)1o=$(\'#\'+5V+\':3i\');if(2P&&1o&&1o.J<1)1o=12;E 1o},an:B(id,2P){E A.2j(id,2P)},o:B(id,2P){E A.4V(id,2P)},dV:B(id,2P){E A.4V(id,2P)},72:B(o){if(!1b(o))A.dW=$f.o(o,K);if(o&&o.e0)o=o[0];E o},4z:B(id){F C=\'\',1o=A.4V(id,K);if(!1o)E C;E 1o.9g()},4E:B(id,1j,ao){F 1o=A.4V(id,K);if(!1o||1j===2D)E V;E 1o.9g(1j,ao)},v:B(o,v,m){E v?A.4E(o,v,m):A.4z(o)},74:B(id){A.o(id).74()},dQ:B(id){A.74(id)},3A:B(id){A.an(id).3A()},dI:B(id){A.3A(id)},dP:B(id){A.2j(id).cP(B(e){if(e.dO==13)E V})},eh:B(s){F o=s;if(!1b(o))o=$o(s);o=o||d.8n[s];if(1b(o)){if(o.ap)o.ap.3S=K;if(o.aq)o.aq.3S=K;if(o.at)o.at.3S=K}},ec:B(o,3T){3T=3T||w.6M;if((3T.6k==13&&3T.e4)||(3T.6k==83&&3T.e7)){A.eb(o);A.3A(o)}},3y:B(s){F o=s;if(!1b(o))o=$o(s);o=o||d.8n[s];if(1b(o))o.3y()},ea:B(s,n){n=n||($f.d6+$f.as);F C=\'\',ar=s.1x(\',\');P(F i=0;i<ar.J;i++){C+=\'<3i 2e="7A" 1c="\'+n+\'" 1j="\'+ar[i]+\'" />\'}E C},cV:B(s){F kc=w.6M.6k;E((kc>=48&&kc<=57)||(s==1&&!C&&kc==46))?K:V},aB:B(jo){1T.2j.aB(jo)},\'\':\'\'};$1R=1M.1R=B(){E T $1R.aC(3g[0])};$1R.dg=B(n){E $v[\'1R.dh\'][n]};$1R.aC=B(){F 11=2b({1j:\'aG\',aL:K,4j:\'\',1u:\'\',aP:\'2K/1V;6U=\'+$c.aM,42:K},3g[0]);F N=11[\'U\'];if(!N)E;if(11[\'2V\'])11[\'1u\']=(11[\'1u\']?11[\'1u\']+\'&\':\'\')+(4M(11[\'2V\'])||1b(11[\'2V\'])?$U.5S(11[\'2V\']):11[\'2V\']);11[\'4j\']=11[\'4j\'].dA();if(!11[\'4j\'])11[\'4j\']=11[\'1u\']?\'aD\':\'ds\';11[\'2G\']=11[\'2G\']||11[\'dk\'];11[\'2d\']=11[\'2d\']||11[\'dj\'];11[\'2O\']=11[\'2O\']||11[\'di\'];F 19=$b.8s(),1X=12;if(!19)E;if(11[\'aL\'])N=$U.2Q(N,$c.aN(1));if($c.9A||8i(\'1R\'))5f.t(\'1R\',N);19.8r(11[\'4j\'],N,11[\'42\']);if(19.aO)19.aO(11[\'aP\']);F aJ=B(){if(19.aK==4){if(19.1l==bk||19.1l==jx){if(11[\'2O\'])11[\'2O\']($1R.1j(19,11[\'1j\']))}R if(19.1l==0){}R{if(11[\'2d\']){if(65(11[\'2d\']))11[\'2d\'](19.1l,19.5g);R 2X(\'cO: \'+N+\'\\jA: \'+19.1l+\'\\jB:\\n\'+19.5g+\'\')}}}R{if(11[\'2G\'])11[\'2G\'](19.aK)}};19.jw=aJ;if(11[\'4j\']==\'aD\')19.jq(\'jr-ju\',\'9d/x-9q-2j-jt\');19.2V(11[\'1u\']);if(!$b.ie&&11[\'42\']==V){if(11[\'2O\'])11[\'2O\']($1R.1j(19,11[\'1j\']))}};$1R.1j=B(19,t){F 1X=\'\';2T(t){O 1:O\'2K\':1X=19.5g;L;O 2:O\'X\':1X=$b.ie?19.5k.X:19.5g;L;O 3:O\'aG\':1X=19.5k;L;O 4:O\'2F\':1X=$34.cR(19.5k);L;O 5:O\'3q\':1X=$34.7h(19.5k);L;O 6:O\'91\':1X=$34.4Y($b.ie?19.5k.X:19.5g);L;3H:1X=19;L};E 1X};Z.jO=B(s){F 21=[];A.is=B(s){F C=V;P(F i=0;i<21.J;i++){if(21[i]==s){C=K;L}}E C};A.4Z=B(){E 21.J};A.1P=B(n){E 21[n-1]};A.3t=B(s){if(s)21[21.J]=s};A.3V=B(){P(F i=0;i<21.J;i++){if(21[i])21[i]()}}};Z.jP=B(){F 6N=V,5i=T 26(),5h=\'91\',6R=12;F X=\'\',1J=12,1a=12,9o=12;A.cf=B(){E X};A.jQ=B(dt,15){5h=dt;6R=15};A.2S=B(G){F W=A;if(!G[\'1j\'])G[\'1j\']=\'X\';if(!G[\'2O\'])G[\'2O\']=B(X){W.42(X)};$1R(G)};A.42=B(X){if(3z(5h,\'91\')>0){A.1J=$34.jL(X);A.1a=A.1J.2y(\'F\');if(3z(5h,\'3q\')>0)A.9o=A.1J.8Y(\'1P\')}R if(3z(5h,\'3q\')>0){A.9o=$34.7h(X)}6N=K;if(6R)6R();A.3V()};A.3p=B(15){if(!6N)5i[5i.J]=15;R A.9u(15)};A.3V=B(){if(!6N)E;P(F i=0;i<5i.J;i++){A.9u(5i[i])}};A.9u=B(15){if(15)15()}};Z.jE=B(){F 4J=[],6W=V,9a=V;A.is=B(s){E A.6W};A.4Z=B(){E 4J.J};A.3t=B(2e,50){4J.16([0,2e,50])};A.3V=B(2R){if(A.6W)2R();if(A.9a)E;A.9a=K;F 3n=[];P(F n=0;n<4J.J;n++){if(4J[n][0]<1)3n.16(4J[n][2])}F W=A;$.4F(3n,B(){W.6W=K;2R()})}};Z.bL=B(){F 2q=0;Q=[];A.cz=B(){E(2q>0)?V:K};A.3t=B(s){2q++;Q[2q]=s};A.1y=B(s){A.3t(s)};A.2l=B(){F C=\'\';P(F i=1;i<=2q;i++){C+=Q[i]+"\\n"};E C};A.au=B(){F C=\'\';P(F i=1;i<=2q;i++){C+=Q[i]+"\\n"};E C};A.jH=B(){if(2q>0)2X(A.au())}};Z.jI=B(id,u,w,h){F 21=[],9j=[],4g={};A.6O=B(k,v){if($b.ie){21.16(\'<jm 1c="\'+k+\'" 1j="\'+v+\'">\')}R{21.16(\' \'+k+\'="\'+v+\'"\')};9j.16(\' \'+k+\'="\'+v+\'"\')};A.jl=B(n,v){4g[n]=v};A.az=B(){F 6Z=T 26(),k;P(k in 4g){6Z[6Z.J]=k+"="+$U.67(4g[k])}E 6Z.31(\'&\')};A.av=B(v){A.6O($b.ie?\'j1\':\'50\',v)};A.j3=B(v){A.9i=v};if(u){A.av(u)};A.6O(\'j4\',\'j5\');A.b7=B(){F 5E=A.az();if(5E.J>0){A.6O(\'iZ\',5E)}if($b.ie){E\'<4e id="\'+id+\'" 22="\'+w+\'" 2m="\'+h+\'" 6Q="9v" iT="iV:iW-iY-iX-j6-jS" j7="5I://jh.9w.97/jg/9c/ji/9x/jj.jk#63=6,0,0,0">\'+21.31(\'\')+\'</4e>\'}R{E\'<6a id="\'+id+\'" 22="\'+w+\'" 2m="\'+h+\'" 6Q="9v" \'+21.31(\'\')+\' 2e="9d/x-9c-9x" b2="5I://9q.9w.97/go/9E"></6a>\'}};A.2l=B(){F C=\'\';if(!A.9i){C=A.b7()}R{C=\'<3q 22="\'+w+\'" 2m="\'+h+\'" aw="0" j9="0" j8="0"><1t><1i><37 54="7c:9N;7G:7A;"><6a 54="7c:ja;z-9M:0;" jb="jd" id="\'+id+\'" 22="\'+w+\'" 2m="\'+h+\'" 6Q="9v" \'+9j.31(\'\')+\' 2e="9d/x-9c-9x" b2="5I://9q.9w.97/go/9E"></6a><37 54="7c:9N;\'+(($b.ie)?\'99:kn(7P=0);\':\'\')+\'-kb-7P:0;z-9M:10;5l:0;9O:0;jU:#jV;22:\'+w+\'2k;2m:\'+h+\'2k;"><a 4x="\'+A.9i+\'" 6T="7q" 54="k6:k3;9J:a6;22:\'+w+\'2k;2m:\'+h+\'2k;"></a></37></37></1i></1t></3q>\'}E C};A.k4=B(o){if(o){$j(o).1V(A.2l())}R{9f(A.2l())}}};F 5f={e:B(e){2X(A.9z(e))},9z:B(e){F 1f=[];P(F k in e){1f.16(k+\'=\'+e[k])}E 1f.31(58)},2l:B(o,l){if(3z(\'95,4R,9U\',1v(o))>0)E(l>0&&o.J>l)?o.3k(0,l)+\' ...\':o;R E\'[\'+1v(o)+\']\'},4V:B(o,51,2N){E A.o(o,51,2N)},o:B(o,51,2N){if(43(o)){F 1f=[];o.2E();P(F t=1;t<=o.4Z();t++){1f.16(o.ik()+\'=\'+o.iv());o.6o()}2X(1f.31(58));E}F 2U,1X,2q=1,C=\'\';if(2i(2N)||2N==-1)2N=30;1G{P(2U in o){if(2q>=2N){2X(C);C=\'\';2q=1}if(51=="3U")C+=2U+"\\n";R C+=2U+"="+(A.2l(o[2U],9S))+"\\n";2q++}if(C!=\'\'){2X(C);C=\'\'}}1A(e){5f.e(e)}},k7:B(o,51,2N){F 2U,1X,2q=1,C=\'\';if(2i(2N)||2N==-1)2N=20;1G{P(2U in o){if(51=="3U")C+=2U+"\\n";R C+=2U+"="+(A.2l(o[2U],9S))+"\\n";2q++}}1A(e){C=5f.9z(e)}E C},km:B(o){2X(A.2F(o))},ka:B(o){A.o(A.3q(o))},bU:B(o){E A.2p(o)},2F:B(o){if(43(o))E A.2p(o.7e())},3q:B(o){if(8b(o))E A.2p(o.7e())},2p:B(ar,5u){F 1f=T 26();1f.16(\'<3q 5x="2v">\');if(1v(ar[0])==\'4e\'){F 6z=ar[0].J,6j=ar.J,49,4B;if(!2i(5u))1f.16("<1t><1i 4O=\\""+(6z+1)+"\\">"+5u+"</1i></1t>");1f.16("<1t><1i 4O=\\""+(6z+1)+"\\">jY="+6z+", jX="+6j+"</1i></1t>");P(F 2Z=0;2Z<ar.J;2Z++){49=ar[2Z];1f.16("<1t>");1f.16("<1i>"+(2Z+1)+".</1i>");P(4B=0;4B<49.J;4B++){1f.16("<1i>"+49[4B]+"</1i>")}1f.16("</1t>")}}R{if(!2i(5u))1f.16("<1t><1i 4O=\\"2\\">"+5u+"</1i></1t>");if(1b(ar)){F c=0;1f.16("<1t><1i 4O=\\"2\\">9Z={$4Z}</1i></1t>");P(F k in ar){if(1v(ar[k])=="4R"){1f.16("<1t><1i>"+k+".</1i><1i>"+ar[k]+"</1i></1t>");c++}}C=1Z(C,"4Z",c)}R{1f.16("<1t><1i 4O=\\"2\\">9Z="+ar.J+"</1i></1t>");P(F 2Z=0;2Z<ar.J;2Z++){1f.16("<1t><1i>"+(2Z+1)+".</1i><1i>"+ar[2Z]+"</1i></1t>")}}}1f.16("</3q>");E 1f.31(58)},t:B(t,s,id){id=id||\'2v-6H\';o=$j(\'#\'+id);if(7M(s)){s=t;t=\'\'}R{F 9V=t.1x(\':\');if(3z(\'U,1R,kf\',9V[0])>0)s=\'<a 4x="\'+s+\'" 6T="7q">\'+s+\'</a>\';s=\'<t>\'+t+\'</t>\'+s}if(!o.J){o=$(\'<37></37>\').1F(\'id\',id).8N(\'2M\')}o.kl(\'3b\',B(){F 4h=\'#2v-6H in{9J:a6;bg-7H:ke;7K-2m:\'+($w.hs-bk)+\'2k;7G:7A;7G-y:hm;}\';4h+=\'#2v-6H t{7B:#ho;hp-hr:hq;hl-7H:bh;}\';4h+=\'#2v-6H a{7B:#be;}\';$p.47(\'aV\',4h);$(\'<in></in>\').8N(o);o.4h({bg:\'bh he\',hf:\'#hj\',7B:\'#be\',\'2K-6Q\':\'5l\',\'hu-2m\':1.5,\'aw-hF\':5,7P:0.5,7c:\'hC\',iS:40,5l:20,hw:1}).1F(\'3b\',\'K\').5J()});o.2t(\'in\').hv(\'<p>\'+s+\'</p>\')}};8i=B(v){F 1X=$9k.q(\'9A\');if(7x(v)&&v==1X)E K;R E V;if(1X)E K;E V};if(!aH)F aH={hy:B(){}};$9k={q:B(k){E $.59.4c(k)},aZ:B(k){E 3e(A.q(k))},4c:B(k){E A.q(k)}};59=B(k){E $.59.4c(k)};hz=B(k){E 3e($.59.4c(k))};hb=B(k){E 6m($.59.4c(k))};$c0=$2W={gP:B(1U){E 1U.1k(/[^\\gR-\\gS]/g,\'aa\').J},2h:B(1U){F 2h=1U.J,3D=0;P(F i=0;i<2h;i++){if(1U.78(i)<27||1U.78(i)>aI){3D+=2}R{3D++}}E 3D},gU:B(1U,2h){F l=1U.J,7r=[],3D=0;P(F i=0;i<l&&3D<2h;i++){7r[i]=1U[i];if(1U.78(i)<27||1U.78(i)>aI){3D+=2}R{3D++}}E 7r.31(\'\')},aR:B(1U,2h,3L){F C=\'\';if(3L==12)3L=\'..\';3L=3L||\'\';F 7S=/[^\\aF-\\aE]/g,7F=1U.1k(7S,\'**\').J,3s=0,6L=\'\';P(F i=0;i<7F;i++){6L=1U.7o(i).2l();if(6L.7m(7S)!=12)3s+=2;R 3s++;if(3s>2h)L;C+=6L}if(7F>2h)C+=3L;E C},4v:B(C,t,c,3L){if(!C)E\'\';if(t==2){C=C.1k(/<(.*)>.*.<\\/\\1>/ig,\'\');C=C.1k(/<[\\/]?([a-7C-7R-9]+)[^>^<]*>/ig,\'\');C=C.1k(/\\[[\\/]?([a-7C-7R-9]+)[^]^[]*]/ig,\'\');C=C.1k(/(\\r\\n)/ig,\'\');C=C.1k(/&7j;[\\/]?([a-7C-7R-9]+)[^&^;]*&gt;/ig,\'\')}C=r(C,"\\"", "&bc;");C=r(C,"\'", "&#39;");C=r(C,"<","&7j;");C=r(C,">","&gt;");if(c)C=A.aR(C,c,3L);E C},gV:B(s){E A.4v(s,t)},7z:B(s){E s.1k([\'&\',\'"\',"\'",\'<\',\'>\',\'’\'],[\'&gW;\',\'&bc;\',\'&b9;\',\'&7j;\',\'&gt;\',\'&b9;\'])},h6:B(C){E C=C.1k(/<[^>]*>/g,\'\')},h5:B(s,1L,k){if(!k)k=\'1L\';if(!1L)1L=\'{$\'+k+\'}\';E 1Z(1L,k,s)},7s:B(s,n){F C=\'\',s=\'\'+s+\'\',2h=s.J,6s=s.23(\'.\',0);if(6s==-1){C=s+\'.\';P(i=0;i<n;i++){C=C+\'0\'}}R{if((2h-6s-1)>=n){F 3s=1;P(j=0;j<n;j++){3s=3s*10}C=3h.77(7y(s)*3s)/3s}R{C=s;P(i=0;i<(n-2h+6s+1);i++){C=C+\'0\'}}}E C},h8:B(s){E A.7s((7w(s)?s:\'\'),2)},h4:B(1U){F 7E=/(\\d+)(\\d{3})/,x=1U.1x(\'.\'),5W=x[0],aW=x.J>1?\'.\'+x[1]:\'\';b5(7E.2v(5W)){5W=5W.1k(7E,\'$1\'+\',\'+\'$2\')}E 5W+aW},h0:B(n){E 3h.77(3h.7O()*n)},h2:B(5L,7K){E 3h.aX(3h.7O()*(7K-5L+1)+5L)}};$ck=$4D={v:B(k,v,7a){E 7M(v)?A.4E(k,v,7a):4z(k)},4E:B(k,v,7a){F 3w=0;2T(7a){O\'4i\':3w=1;O\'iz\':3w=7;O\'7W\':3w=30;O\'81\':3w=9C;O\'aT\':3w=iB}if(3w>0){F 75=T 1m();75.7v(75.5X()+(3w*24*60*60*3f));F b4=\'; iu=\'+75.im();d.4D=k+\'=\'+v+b4+\'; iq=\'+$c.Q[\'1r\']}E v},4z:B(k){F C=\'\',7Y=T 4N(ir(k)+"=([^;]+)");if(7Y.2v(d.4D+\';\')){7Y.3V(d.4D+\';\');C=b1(4N.$1)}E C},iN:B(1c){F dc=d.4D,79=1c+\'=\',2E=dc.23(\'; \'+79);if(2E==-1){2E=dc.23(79);if(2E!=0)E 12}R{2E+=2}F 5B=d.4D.23(\';\',2E);if(5B==-1)5B=dc.J;E b1(dc.4L(2E+79.J,5B))}};$6Y=$28=1M.28={1K:T 1m(),4u:T 26(),3G:B(){E 3h.77(1m.d()/3f)},v:B(t,3O){E A.2g(3O||A.1K,t)},iQ:B(){A.4u[\'7J\']=A.4u[\'7J\']||A.2g(A.1K,\'1K\');E A.4u[\'7J\']},iK:B(r){if(r)E A.2g(T 1m(),\'28\');A.4u[\'6x\']=A.4u[\'6x\']||A.2g(A.1K,\'28\');E A.4u[\'6x\']},6q:B(s){if(3P(s))E T 1m(s*3f);F d=T 1m();d.7v(1m.2S(s.1k(/-/gi,"/")));E d},7s:B(d){F C=1m.2S(d)/3f;if(C<1)C=0;E C},4P:B(s){if(s.2l().J<2)s=\'0\'+s;E s},2g:B(d,t,s){F C=\'\';d=1b(d)?d:A.6q(d);2T(t){O\'1K\':C=\'Y-M-D\';L;O\'84\':C=\'M-D H:I\';L;O\'7Z\':C=\'Y-M-D H:I\';L;O\'7L\':C=\'y-M-D H:I\';L;O\'69\':C=r(A.2g(d,\'28\'),\' \',\'<br/>\');if(s==\'s\')C=\'<3B 5x="69">\'+C+\'</3B>\';E C;L;O\'28\':C=\'Y-M-D H:I:s\';L;3H:C=t?t:\'Y-M-D H:I:s\';L}C=r(C,\'Y\',d.7N());C=r(C,\'y\',d.7N().2l().3k(2));C=r(C,\'M\',A.4P(d.7X()+1));C=r(C,\'m\',d.7X()+1);C=r(C,\'D\',A.4P(d.7I()));C=r(C,\'d\',d.7I());C=r(C,\'H\',A.4P(d.7g()));C=r(C,\'h\',d.7g());C=r(C,\'I\',A.4P(d.80()));C=r(C,\'i\',d.80());C=r(C,\'S\',A.4P(d.7V()));C=r(C,\'s\',d.7V());E C},hS:B(v,n,p,t){F dn,7k=1,7U=7k*3f,55=7U*60,4a=55*60,4i=4a*24,7W=4a*24*30,81=4i*9C;2T(p){O\'hV\':dn=7k*n;L;O\'s\':dn=7U*n;L;O\'2B\':dn=55*n;L;O\'h\':dn=4a*n;L;O\'m\':dn=7W*n;L;O\'y\':dn=81*n;L;O\'d\':3H:dn=4i*n;L}F 3O=T 1m(T 1m(T 1m(v.1k(\'-\',\',\'))).hL()+dn);E t?A.2g(3O,t):3O},hM:B(t,d1,d2){F a7=1m.2S(d1.1k(/-/gi,"/")),ad=1m.2S(d2.1k(/-/gi,"/"));F C=a7-ad;2T(t){O\'h\':C=C/hN;L;O\'m\':C=C/ah;L;O\'s\':C=C/3f;L}E C},hO:B(n,1O){if(!1O)1O=[];if(!1O[\'4i\'])1O[\'4i\']=$v[\'1K.1O\'][3];if(!1O[\'4a\'])1O[\'4a\']=$v[\'1K.1O\'][2];if(!1O[\'55\'])1O[\'55\']=$v[\'1K.1O\'][1];F C=\'\',d=h=m=0,hY=n;if(n>60){m=n%60;n=(n-m)/60;C=m+1O[\'55\']}if(n>24){h=n%24;n=(n-h)/24;C=h+1O[\'4a\']+C}if(n>0){C=n+1O[\'4i\']+C}E C}};$U=1M.U={is:B(s){E A.9R(s)},9R:B(s){E(s.23(\'://\')!=-1||s.4L(0,1)==\'/\')?K:V},ih:B(s){E i7.3V(T 4N("^(5I(s?)|i1)://","i"))},5S:B(ar){F C=\'\';P(k in ar)C+=\'&\'+k+\'=\'+A.67(ar[k]);if(C.J>0)C=C.4L(1);E C},2Q:B(C,4C){C=C||\'\';if(4M(4C))4C=A.5S(4C);if(4C){if(C.23(\'?\')==-1)C+=\'?\';R if(C.4L(C.23(\'?\')+1).J>0)C+=\'&\';C+=4C}E C},i5:B(s,v,k){k=k||\'4B\';v=v||3h.7O();E A.2Q(s,k+\'=\'+v)},i4:B(N,cG,3u){3u=2b({4W:\'\'},3u);F C=3u.4W;if(N!=\'\'&&N!=\'5I://\'){C=\'<a 4x="\'+N+\'"\';if(3u.6T)C+=\' 6T="7q"\';if(3u.5O)C+=\' 5O="\'+3u.5O+\'"\';if(3u.cF)C+=\' \'+3u.cF;C+=\'>\'+cG+\'</a>\'}E C},en:B(s){E A.67(s)},67:B(s){E i3(s)},i2:B(s){E i0(s)},de:B(s){E A.bl(s)},i6:B(s){E ib(s)},bl:B(s){E ia(s)},\'\':\'\'};$5e[\'5Z\']={};$i8=$5Z={go:B(4W,1D,1C,4y){if(!4W)4W=\'i9\';if(!1D)1D=\'hZ\';if(!1C)1C=\'{$1C}\';if(!4y)4y=0;F N=$(\'3i[1c="\'+4W+\'"]\').2C(),6V=3e($(\'3i[1c="\'+1D+\'"]\').2C())+4y;if(6V<1)6V=0;N=r(N,1C,6V);$p.go(N)},hP:B(U,7D,5Y,1L){if(!3P(5Y))5Y=10;F C=\'\',N=\'\',2L=3h.77(7D/5Y);if((2L*5Y)<7D)2L++;if(1L&&2L>1){P(F i=2;i<4;i++){if(i>2L)L;N=1Z(U,\'1C\',i);C+=\'<a 4x="\'+N+\'" 5O="cs \'+i+\'">\'+i+\'</a> \'}if(2L>3){if(2L>4)C+=\' ..\';i=2L;N=1Z(U,\'1C\',i);C+=\'<a 4x="\'+N+\'" 5O="cs \'+i+\'">\'+i+\'</a> \'}}R{C=1Z(1L,\'U\',1Z(U,"1C",2L))}E C}};hK=B(){E T Z.2Y()};hQ=B(){E T Z.3x()};hR=B(){E T Z.5o()};hW=B(){E T Z.45()};43=B(o){F C=V;if(1b(o)){1G{if(o.3o()=="Z.2Y")C=K}1A(e){}}E C};8b=B(o){F C=V;if(1b(o)){1G{if(o.3o()=="Z.3x")C=K}1A(e){}}E C};hX=B(o){F C=V;if(1b(o)){1G{if(o.3o()=="Z.5o")C=K}1A(e){}}E C};hU=B(o){F C=V;if(1b(o)){1G{if(o.3o()=="Z.45")C=K}1A(e){}}E C};6C(Z,{68:\'14\',hT:\'53\',ij:\'il\',iI:\'iJ\',iH:\'.\',iG:B(o){F C=\'\';if(w.5v)C=o.2K;R{1G{C=o.98[0].9t}1A(ex){C=\'\'}}E C}});$34=1M.34={iE:B(s,2f,1w){if(!2f)2f=\';\';if(!1w)1w=\'=\';F C=T Z.2Y();if(!s)E C;F 3n=s.1x(2f),ar;P(F a=0;a<3n.J;a++){ar=3n[a].1x(1w);if(ar[0].J>0)C.1y(ar[0],ar[1])}E C},cR:B(X){F 1Y=T Z.2Y();if(X){F 14=T Z.45();if(1b(X))14.5w(X);R 14.5s(X);14.6t();F 4l=14.5b(\'17\').1x(\',\');P(F a=0;a<4l.J;a++){1Y.82(14.6b(4l[a]),4l[a]+\'.\')}}E 1Y},7h:B(X){F 44=T Z.3x();if(X){F 14=T Z.45();if(1b(X))14.5w(X);R 14.5s(X);14.6t();F 2a=14.9b();if(2a!=\'\'){44.6f(2a);14.9y();14.5p();P(F a=0;a<14.6I();a++){44.1y(14.2y());14.5q()}}}E 44},4Y:B(X){F 5m=T Z.5o();if(X){F 14=T Z.45();if(1b(X))14.5w(X);R 14.5s(X);14.6t();5m.1y(\'F\',14.6b(\'F\'));F 3l,a,i,2a=14.9b();if(2a!=\'\'){3l=T Z.3x();3l.6f(2a);14.9y();14.5p();P(a=0;a<14.6I();a++){3l.1y(14.2y());14.5q()}5m.1y(14.bx(),3l)}F 4l=14.5b(\'7l\').1x(\',\');P(a in 4l){F 5z=4l[a];2a=14.5b(\'1D.\'+5z);if(5z&&2a){3l=T Z.3x();3l.6f(2a);14.9s(5z);14.5p();P(i=0;i<14.6I();i++){3l.1y(14.2y());14.5q()}5m.1y(5z,3l)}}}E 5m},iF:B(1J){E 1b(1J)?1J:A.4Y(1J)}};$8M=1M.bw={iL:"$$$",iP:"~~~$$$~~~",iO:"(.+?)",iM:"([\\w\\-\\iD\\.\\:]*)",bG:"([^>\\"]*)",iC:A.bG+"(!([\\w-\\.][^!]+?))?",it:"([^{\\$\\"]*)",ip:"([\\s\\w-\\.\\:=;\\\'\\(\\)<>\\[\\]{\\$}]*)",io:"(,\\"(.|[^>\\"]*)\\")?",iw:"([\\s\\S.]*?)",iA:"((.|\\n)*?)",ix:"(!([\\w-\\.\\:]+?))?",iy:"(.+?)",6u:/\\{\\@([^{\\@}]*)}/gi,6h:/\\{\\$([^{\\$}]*)}/gi,hJ:/\\{\\${\\$2k}([^{\\$}]*)}/gi,hI:"<F:([^<>]*)>",h1:"<F:([^<>!]*)(!([\\w-\\.][^!]+?))?>",gZ:"<bO:([^<>]*)>",gX:"<bO:([^<>!]*)(!([\\w-\\.][^!]+?))?>",gY:"<38:([^<>]*)>",h3:"<38:([^<>!]*)(!([\\w-\\.][^!]+?))?>",h9:/\\[1P:([\\w-\\.]*)\\]/gi,bn:/\\[1P:([\\w-\\.]*)(!([\\w-\\.][^!]+?))?(!([\\w-\\.][^!]+?))?\\]/gi,4q:B(C,1I,3I,15){E $c.4q(C,1I,3I,15)},bC:B(C,1I){E A.4q(C,1I,A.6u)},8w:B(C,1I){E A.4q(C,1I,A.6h)},bp:B(C,1I,3I,15){if(!C)E\'\';E C.1k(3I,B(s,1E,32,bt,h7,bq){E $8M.bo(1I.3j(1E),bt,bq,15)})},4G:B(C,1s,3I){E A.bp(C,1s,3I||A.bn)},bo:B(C,4H,1w,15){if(4H){2T(4H){O\'1V\':C=$2W.4v(C,1,1w);L;O\'gL\':C=$2W.4v(C,2,1w);L;O\'1K\':C=$28.2g(C,\'1K\');L;O\'84\':C=$28.2g(C,\'84\');L;O\'7Z\':C=$28.2g(C,\'7Z\');L;O\'28\':C=$28.2g(C,\'28\');L;O\'69\':C=$28.2g(C,\'69\');L;O\'7L\':C=$28.2g(C,\'7L\');L;O\'gM\':C=$2W.4v(C,0,1w);L;O\'2K\':C=$2W.4v(C,1,1w);L;O\'X\':C=$2W.7z(C);L;3H:if(15)C=15(C,4H,1w);R{F 5P;if($2W[5P=\'gK\'+4H])C=$2W[5P](C,1w);R if($2W[5P=\'gJ\'+4H])C=$2W[5P](C,1w)}L}}E C},7z:B(17,1D,6X){if(!17)17=\'1P\';F C=T by();C.16(A.7t());C.16(\'<53>\');C.16(\'	<17>\'+17+\'</17>\');C.16(\'	<1D>\'+1D+\'</1D>\');C.16(\'</53>\');C.16(6X);C.16(A.7p());E C.31(58)},gH:B(17,1D,6X){if(!17)17=\'1P\';F C=T by();C.16(A.7t());C.16(\'<53>\');C.16(\'	<7l>\'+17+\'</7l>\');C.16(\'	<1D.\'+17+\'>\'+1D+\'</1D.\'+17+\'>\');C.16(\'</53>\');C.16(6X);C.16(A.7p());E C.31(58)},7t:B(){E\'<\'+\'?X 63="1.0"?\'+\'><14 63="1.0" gI="38">\'},7p:B(){E\'</14>\'}};Z.bw={};Z.2Y=B(){A.1H=-1,A.1g=0,A.Q=T 26(),A.3m=\'Z.2Y\';A.7i=A.is=B(){E 3P(A.1H)};A.3o=B(){E A.3m};A.5n=A.4Z=B(){E A.1H+1};A.gN=A.2h=B(){E A.Q.J};A.bW=A.i=B(){E A.1g};A.7e=B(){E A.Q};A.6F=A.2E=B(){A.1g=0};A.gO=B(){A.1g=(A.1H>-1?A.1H:0)};A.85=A.6o=B(n){if(!3P(n))n=1;A.1g+=n;if(A.1g>A.1H)A.1g=A.1H;if(A.1g<0)A.1g=0};A.7T=A.ik=B(){E(A.1H>-1)?A.Q[A.1g][0]:\'\'};A.4U=A.iv=B(){E(A.1H>-1)?A.Q[A.1g][1]:\'\'};A.1y=A.3t=B(k,v){P(F i=0;i<=A.1H;i++){if(A.Q[i][0]==k){A.Q[i][1]=v;E}}A.1H++;A.Q[A.1H]=T 26(k,v)};A.7n=B(k,v){P(F i=0;i<=A.1H;i++){if(A.Q[i][0]==k){A.Q[i][1]=v;E}}};A.c7=B(k){P(F i=0;i<=A.Q.J;i++){if(A.Q[i][0]==k){A.Q=A.Q.ce(0,i).gT(A.Q.ce(i+1,A.Q.J));A.1H=A.Q.J-1;A.6F();L}}};A.bX=B(k){F C=V;P(F i=0;i<=A.1H;i++){if(A.Q[i][0]==k){C=K;L}}E C};A.3j=A.v=B(k){F C=\'\';P(F i=0;i<=A.1H;i++){if(A.Q[i][0]==k){C=A.Q[i][1];L}}E C};A.gQ=A.ha=B(k){E 3e(A.3j(k))};A.hA=A.hx=B(k){E 6m(A.3j(k))};A.bV=B(){E A.ch(0)};A.c1=B(){E A.cn(0)};A.ch=B(t){if(!7u(t))t=1;F C=\'\';P(F i=0;i<=A.1H;i++){C+=\',\'+A.Q[i][1]}if(C.J>0)C=C.3k(1);E C};A.cn=B(t){if(7u(t))t=1;F 1f=T 26();P(F i=0;i<=A.1H;i++){1f[i]=A.Q[i][t]}E 1f};A.82=B(1S,2k){if(43(1S)){if(!2k)2k=\'\';1S.6F();P(F i=0;i<1S.5n();i++){A.1y(2k+1S.7T(),1S.4U());1S.85()}}};A.bz=B(2a){F 41=2a?2a.1x(\',\'):[];P(F a in 41){F 1D=41[a];if(1D)A.7Q(hH.2S(A.v(1D)),1D+\'.\')}};A.7Q=B(5d,2k){P(F 17 in 5d){if(1v(5d[17])==\'4e\')A.7Q(5d[17],2k+17+\'.\');R A.3t(2k+17,5d[17])}}};Z.3x=B(){A.1n=-1,A.2A=-1,A.1g=1,A.Q=T 26(),A.3m="Z.3x";A.7i=A.is=B(){E A.1H>-1?K:V};A.3o=B(){E A.3m};A.hG=A.8E=B(){E A.1n};A.hD=A.hE=B(){E A.2A+1};A.bW=A.i=B(){E A.1g};A.7e=B(){E A.Q};A.5p=A.2E=A.ht=B(){A.1g=1};A.hg=A.5B=A.hc=B(){A.1g=(A.1n>0?A.1n:1)};A.5q=A.6o=A.hd=B(2s){if(!3P(2s))2s=1;A.1g+=2s;if(A.1g>A.1n)A.1g=A.1n;if(A.1g<1)A.1g=1};A.1y=A.3t=B(1S){if(43(1S)){if(A.1n<0&&1S.5n()>0){A.1n=0;A.2A=1S.5n()-1;A.Q[A.1n]=1S.c1()}if(A.1n<0)E;A.1n++;A.Q[A.1n]=T 26(A.2A+1);P(F c=0;c<=A.2A;c++){A.Q[A.1n][c]=1S.3j(A.Q[0][c])}}};A.7n=B(1S){if(43(1S)&&A.1n>0){P(F c=0;c<=A.2A;c++){A.Q[A.1g][c]=1S.3j(A.Q[0][c])}}};A.hk=B(k,v){if(A.1n>0){P(F c=0;c<=A.2A;c++){if(A.Q[0][c]==k){A.Q[A.1g][c]=v;L}}}};A.c7=B(s){if(A.1n>0){F 6j=3P(s)?s:A.1g,49=A.Q;A.1n=-1;A.Q=T 26();P(F a=0;a<49.J;a++){if(a!=6j){A.1n++;A.Q[A.1n]=49[a]}}A.1g=1}};A.2y=B(){F 1Y=T Z.2Y();if(A.1n>0){P(F c=0;c<=A.2A;c++)1Y.1y(A.Q[0][c],A.Q[A.1g][c])}E 1Y};A.4U=A.iv=B(k){F C=\'\';if(A.1n>0){P(F c=0;c<=A.2A;c++){if(A.Q[0][c]==k){C=A.Q[A.1g][c];L}}}E C};A.hn=A.iR=B(k){E 3e(A.4U(k))};A.kk=A.kh=B(k){E 6m(A.4U(k))};A.bV=B(){F C=\'\';if(A.1n>0){P(F c=0;c<=A.2A;c++)C+=\',\'+A.Q[0][c];if(C.J>0)C=C.3k(1)}E C};A.6f=B(2a){F 41=2a.1x(\',\');if(41.J>0){A.1n=0;A.2A=41.J-1;A.Q=T 26();A.Q[A.1n]=41}};A.82=B(2F){if(1b(2F)&&2F.3o()==A.3m){2F.6F();P(F i=0;i<2F.5n();i++){A.1y(2F.7T(),2F.4U());2F.85()}}}};Z.5o=B(){A.29={},A.3m=\'Z.5o\';A.7i=A.is=B(){E A.29.J>0?K:V};A.3o=B(){E A.3m};A.bX=B(k){E 1v(A.29[k])!=\'2D\'};A.1y=A.3t=B(k,o){A.29[k]=o};A.7n=B(k,o){A.29[k]=o};A.3j=B(k,o){E A.29[k]};A.8H=B(k,o){E(1v(A.29[k])==\'4R\')?A.29[k]:\'\'};A.kj=B(k,o){F 1f=T 26();if(4M(A.29[k]))1f=A.29[k];E 1f};A.2y=B(k,o){F 1Y=T Z.2Y();if(43(A.29[k]))1Y=A.29[k];E 1Y};A.8Y=B(k,o){F 44=T Z.3x();if(8b(A.29[k]))44=A.29[k];E 44}};Z.45=B(){A.1g=0,A.1B=12,A.1N=V,A.96=12,A.5y=12,A.3N=0,A.3J=0,A.bT=[],A.6G=\'\',A.5a=\'\',A.3m="Z.45";A.5t=B(){F o=12;if(w.5v){F 9p=[\'c9.k8\'];P(F i=0;o<9p.J;i++){1G{o=T 5v(9p[i])}1A(e){o=12}if(o!=12)L}}R if(d.9n&&d.9n.co)o=d.9n.co(\'\',\'\',12);E o};A.ki=B(2s){E A.1N};A.3o=B(){E A.3m};A.kg=B(s){A.1B=A.5t();if(A.1B!=12){A.1B.42=V;A.1B.cd=V;A.1N=K}};A.5w=B(o){if(1b(o)){1G{A.1B=o.kd;A.1N=K}1A(e){A.1N=V}}};A.k1=B(2s){A.1B=A.5t();1G{A.1B.2G(2s);A.1N=K}1A(e){A.1N=V}};A.jZ=B(2s){A.1B=A.5t();1G{A.1B.2G(2s);A.1N=K}1A(e){A.1N=V}};A.5s=B(2s){if(!$b.ie){A.5w(T k0().k5(2s,\'2K/X\'))}R{A.1B=A.5t();1G{A.1B.42=V;A.1B.cd=V;A.1B.5s(2s);A.1N=K}1A(e){A.1N=V}}};A.cf=B(){E(A.1N==K)?A.1B.X:\'\'};A.6t=B(){if(!A.1N||!A.1B)E;1G{A.96=A.6d(\'53\');A.6G=A.5b(\'17\');A.5a=A.5b(\'1D\')}1A(e){}};A.9s=B(s){if(!A.1N)E;if(s.J>0){A.5y=A.6d(s);A.3N=A.5y.J;if(A.5a.J>0)A.bT=A.5a.1x(\',\')}};A.9y=B(){A.9s(A.6G)};A.5b=B(k){E A.93(A.96,0,k)};A.bx=B(){E A.6G};A.9b=B(){E A.5a};A.k9=B(){E A.5a};A.6I=B(){E A.3N};A.k2=B(){E A.3N};A.jT=B(){E A.3J};A.5p=B(){E A.3J=0};A.5q=B(){A.3J++;if(A.3J>(A.3N-2))A.3J=A.3N-1};A.3j=B(k){F C=\'\';if(A.1N&&A.3N>0){C=A.93(A.5y,A.3J,k)}E C};A.2y=B(){F 1Y=T Z.2Y();if(A.1N&&A.3N>0){1Y=A.6b(A.5y,A.3J)}E 1Y};A.6d=B(s){F C=12;if(A.1N==K){if($b.ie){C=A.1B.6p(Z.68+\'/\'+s)}R{C=A.1B.jW.6p(Z.68)[0].6p(s)}}E C};A.93=B(17,3X,3U){F C=\'\';1G{if(17==12)17=A.1B;C=17.1P(3X).6p(3U).1P(0).9m.9t}1A(e){}E C};A.6b=B(17,3X){F 1Y=T Z.2Y();1G{if(!3X)3X=0;if(!1b(17))17=A.6d(17);if(17&&17.J){F 3Z;if($b.ie){3Z=17.1P(3X).98}R{F n=0;P(F i=0;i<17.J;i++){if(17[i].jR.bM==Z.68){if(3X==n){3Z=17.1P(i).98;L}n++}}}F 6l,1W;P(F c=0;c<3Z.J;c++){6l=3Z.1P(c).bM;if(!2i(6l)){1W=\'\';if(1b(3Z.1P(c).9m))1W=3Z.1P(c).9m.9t;1Y.1y(6l,1W)}}}}1A(e){}E 1Y}};Z.4m=B(){A.99=\'94\';A.U=\'/p.\'+$c.6g+\'/{$25}/{$p}/{$m}/{$2B}.{$x}?3F={$3F}\';A.4g={25:\'\',p:\'\',m:\'\',2B:\'\',x:\'x\',3F:\'\'};A.bP=B(k,v){A.4g[k]=v};A.bQ=B(U,f){if(A.99==\'94\'||f){U=r(U,\'/.\',\'.\');U=r(U,\'/.\',\'.\');U=r(U,\'/.\',\'.\')}E U};A.3v=B(G){G=2b(A.4g,G);F N=A.U;N=1Z(N,\'25\',G.25);N=1Z(N,\'p\',G.p);N=1Z(N,\'m\',G.m);N=1Z(N,\'2B\',G.2B);N=1Z(N,\'x\',G.x);N=1Z(N,\'3F\',G.3F);N=A.bQ(N);F 1u=G.1u;if(1u)N=$U.2Q(N,(4M(1u)||1b(1u)?$U.5S(1u):1u));E N}};$2j={61:B(W){if(!W)W=A;F 3E=K,6S=V,1Q={};W.4b=T Z.bL(),W.2r=12;if(1v 9e!=\'2D\'){9e.jc();6S=K}W.7b.2t(\'3i,je,jf\').5C(B(i){F 2x=$(A),2c=2x.1F(\'1c\');F 1c=(2c&&2c.3k(-2)==\'[]\')?2c.3k(0,2c.J-2):2c;if(1c&&1v(1Q[1c])==\'2D\'){F 1W=2x.9g();1Q[1c]=1W;if(6S&&!9e.iU(2x))3E=V;if(!6S){F cQ=3e(2x.1F(\'cp\')?2x.1F(\'cp\'):(2x.1F(\'5L\')?2x.1F(\'5L\'):2x.1F(\'j0\')));if(cQ>0&&!2x.2C().J)3E=V}if(!3E&&!W.2r)W.2r=2x}});W.7b.2t(\'3i[2e=j2]:jJ\').5C(B(){F 1c=$(A).1F(\'1c\');F 1j=$(A).2C();if(1c)1Q[1c]=1j});if(W.G&&W.G.cD){F 5M=\'\';if(W.G.cH)5M=$6Y.3G();1Q[\'jG\']=5M;F 9h=W.G.cI.1x(\',\'),4f;P(F a in 9h){4f=9h[a];if(1Q[4f]){1Q[4f]=$.cx(1Q[4f]);if(5M)1Q[4f]=$.cx(1Q[4f]+\',\'+5M)}}}W.1Q=1Q;if(W.9l)W.9l();if($9k.q(\'9A\')==\'38\')5f.o(W.1Q);if(3E&&W.4b)3E=W.4b.cz();if(!3E&&W.1z&&W.4b)W.1z(\'2d\',W.4b.2l(),K);E 3E},9l:B(){},86:B(){E 36.86(K)},2V:B(){if(!A.86())E;F W=A;if(!A.61()){E}if(A.9r)E;A.9r=K;F N=A.3v(\'2V\'),5T=A.1Q;if(!N){3a.1z(\'5G\',\'jF jK cO!\',K);E}$1R({U:N,2V:5T,1j:\'X\',2O:B(o){W.cv(o)},2d:K})},cv:B(X){A.1J=$34.4Y(X);A.1a=A.1J.2y(\'F\');F 1q=A.1a.v(\'1l\');if(1q==\'2z\'){F 1e=A.8m(A.1a,\'发布成功！\');3a.1z(\'2z\',1e,K);if(A.3y)A.3y();if(A.6n)A.6n();A.cr()}R{A.8p(A.1a)}A.9r=V},cr:B(){},8o:B(G){G=2b({1z:\'1z\',64:K},G);E G},ct:B(2C,G){G=A.8o(G);F C=V;F 1q=1b(2C)?2C.v(\'1l\'):2C;if(1q==\'2z\')C=K;if(G.64&&1q==\'64\')C=K;E C},jM:B(1a,G){if(A.ct(1a,G))E K;A.8p(1a,G)},8m:B(1a,8q,4b){F 1e=1a.v(\'2H\')||1a.v(\'2H.4R\')||\'\';if(!1e&&4b)1e=1a.v(\'jN\')||1a.v(\'c5\')||\'\';if(!1e&&8q)1e=8q;E 1e},8p:B(1a,G){G=A.8o(G);F 76={};F 1q=G.1l?G.1l:1a.v(\'1l\');F 1e=A.8m(1a,\'\',K);if(!1e)1e=G[\'bN\'+1q];if(!1e){2T(1q){O\'3b\':1e=\'数据初始化！\';L;O\'4T\':1e=\'无效的数据解析！\';L;O\'64\':1e=\'数据已处理！\';L;O\'1u\':1e=\'缺少必要的参数！\';L;O\'38\':1e=\'不正确的数据提交！\';L;O\'jD\':1e=\'不正确的数据提交！\';L;O\'jC\':1e=\'记录不存在！\';L;O\'jn\':1e=\'权限不足！\';L;O\'2z\':1e=\'提交成功！\';L;O\'jp\':3H:1e=\'数据处理失败！\';L}}76.1l=1q;76.2H=1e;if(!1e)2X(X);R{F cE=G[\'jv\'+1q]||\'5G\';if(G.1z){2T(G.1z){O\'8y\':1T.8y.5J(1e);L;O\'1z\':3H:3a.1z(cE,1e,K);L}}if(G.2R)G.2R.4I(A,1q,1e)}E 76},\'\':\'\'};Z.8n=B(G,1p){A.G=2b({8x:\'jz\',bu:\'服务\',gG:\'未知的服务请求！\',jy:\'请填写必要的信息！\',hB:\'未知错误\',fZ:\'处理中..\',dp:\'处理成功！\',dr:\'服务跳转中..\',5Q:K,bJ:\'提交中..\',bH:\'提交成功！\',bK:dz,bE:2,cq:K,c6:K,cD:V,cH:V,cI:\'\',cj:\'c8\',8v:[],cN:\'\',5H:\'\',cT:K,8g:K},G);A.1p=2b({3A:\'[el=3A]\',1z:\'.1z\',cU:\'8k\',d3:\'3B\',\'\':\'\'},1p);A.5r=B(G){if(G)A.G=2b(A.G,G)};A.cA=B(1p){if(1p)A.1p=2b(A.1p,1p)};A.e3=A.bs=B(G,1p){if(A.cw)E;A.cw=K;A.5r(G);A.cA(1p);A.1h=A.1h||A.ef;A.G.56=A.G.56||A.G.2M;if(A.G.56||A.1h){A.1h=A.1h?$jo(A.1h):$jo(A.G.56);if(A.1h)A.2u=A.1h.2t(\'2j\')}R if(A.G.8x||A.2u){A.2u=A.2u?$jo(A.2u):$f.2j(A.G.8x);if(A.2u)A.1h=A.2u.dY()}if(!A.1h&&!A.2u)E;A.62=A.1p.62?A.1p.62:A.1h.8K(A.1p.1z);A.2I=A.1p.2I?A.1p.2I:A.1h.8K(A.1p.3A);A.4s=K};A.dZ=B(k,v){A.G.8v[k]=v},A.4m=B(1u){F C=A.G.5H?A.G.5H:A.G.cN;C=$c.8w(C,A.G.8v);if(1u)C=$U.2Q(C,1u);E C},A.61=B(){if(!A.7b)A.7b=A.1h;E $2j.61(A)};A.dU=B(){if(!A.4s)E;A.1h.6A()};A.bm=B(G){F W=A;if(A.2u){A.2u.3A(B(){E V});if(A.G.cT){A.2u.2t(\'3i\').4o(\'cP\',B(et){if(et.6k==\'13\')W.4T()})}}if(!A.2I)E;A.2I.bI(B(){if($(A).1F(\'3S\'))E;W.4T();E V})};A.4r=B(1l,6e){if(!A.2I)E;A.5Q=1l;F 1W=6e;if(!A.G.8l)A.G.8l=A.2I.2K();if(1T.3W){if(1l==\'8d\')1T.3W.cK();R 1T.3W.cM()}2T(1l){O\'8d\':O\'dm\':A.2I.1F(\'3S\',K);if(!1W)1W=A.G.bJ;L;O\'2z\':A.2I.1F(\'3S\',K);if(!1W)1W=A.G.bH;L;O\'4o\':3H:A.2I.1F(\'3S\',V);if(!1W)1W=A.G.8l;L}if(A.G.5Q&&1W)A.2I.2t(\'3B\').2K(1W)};A.cg=B(){E A.5Q&&A.5Q!=\'4o\'};A.3y=B(){A.2u.5C(B(){A.3y()});A.4r(\'4o\')};A.1z=B(1l,2H,2R,3G){3G=3G?3G:A.G.bE;if(1l==\'6A\')3a.8k(A.62,\'6A\');R 3a.8k(A.62,2H,{1l:1l,2R:2R,d7:\'.bF\',dG:\'bF\',e6:A.G.bK,3G:3G,ep:A.G.cq})};A.4t=B(3U){E A.G[\'bN\'+3U]||\'[\'+3U+\']\'};A.6P=B(U){if(U)A.G.5A=U;E A.G.7d||A.G.5A||$c.U(\'dL\')};A.8c=B(G,1p){A.bs(G,1p);if(!A.4s)E;A.bm()};A.cm=B(){if(!A.61()){F 87=A.4t(\'eg\');if(A.2r){F 2c=A.2r.1F(\'bu\');if(!2c)2c=A.2r.1F(\'e8\');if(!2c)2c=A.2r.1F(\'d4\');if(!2c)2c=A.2r.1F(\'1c\');87=\'请输入 \'+2c+\'！\';A.2r.d8(\'2d\');A.2r.df(B(){F jo=$(A);if(jo.2C())jo.db(\'2d\')});A.2r.74()}A.1z(\'2d\',87);if(A.G.cb)A.G.cb(A.2r);E V}E K},A.4T=B(){if(A.cg())E;if(!A.cm())E;A.1z(\'2G\',A.4t(\'4T\'));A.4r(\'8d\');F W=A;F N=A.4m(),5T=12;if(!N){A.1z(\'5G\',A.4t(\'4m\'));A.4r(\'4o\');E}if(A.G.cj==\'c8\')5T=A.1Q;R N=$U.2Q(N,$U.5S(A.1Q));$1R({U:N,2V:5T,1j:\'X\',2O:B(o){W.bY(o)},2d:K});E V};A.bY=B(X){if(A.dD||8i(\'1J\')){F 1d=$(\'#du\');if(!1d||!1d.J)2X(X);R 1d.1V($c0.4v(X));A.4r(\'4o\');E}F W=A;A.1J=$34.4Y(X);A.1a=A.1J.2y(\'F\');F 1q=A.1a.v(\'1l\');if(3z(\'2z,64\',1q)>0){if(A.1a.v(\'7d\'))A.6P(A.1a.v(\'7d\'));if(A.1a.v(\'5A\'))A.6P(A.1a.v(\'5A\'));A.4r(\'2z\');if(A.G.c6)W.c2();if(A.G.2R)A.G.2R.4I(A,1q,A.1a)}R{A.1z(\'2d\',A.1a.v(\'2H\')||A.1a.v(\'c5\')||A.4t(\'do\')+\'(\'+1q+\')\',K);A.4r(\'4o\');if(A.G.c3)A.G.c3.4I(A,1q,A.1a)}};A.c2=B(G){F W=A;G=2b({2H:A.4t(\'2z\')},G);F 8T=B(){if(!W.G.8g)E;1T.8y.5J(W.4t(\'c4\'));$p.go(W.6P())};if(A.G.7d||A.G.5A){1T.gF.5J({1l:\'2z\',2H:G.2H,fQ:8T})}A.1z(\'2z\',G.2H,8T,1)}};Z.bU=B(bZ){A.5r=B(G,4w){if(!4w)E G;if(4w.4n)4w.4n=2b(G.4n,4w.4n);G=2b(G,4w);E G};A.G=A.5r({cc:\'1P\',4n:{x:\'x\'}},bZ);A.g4=B(k,v){A.G.4n[k]=v};A.ci=B(G){G=2b(A.G.4n,G);E $U.2Q(1T.4m.3v(G),\'1C=\'+A.1C)};A.3v=B(){E A.G.5H?$U.2Q(A.G.5H,\'1C=\'+A.1C):A.ci()};A.3b=B(G){A.G=A.5r(A.G,G);if(A.G.56)A.1h=$j(A.G.56);if(A.G.8U)A.1d=$j(A.G.8U);if(A.G.1L)A.33=$j(A.G.1L);if(A.G.8Z)A.3C=$j(A.G.8Z);if(A.G.5Z)A.4k=$j(A.G.5Z);if(A.G.cl)A.35=$j(A.G.cl);if(A.G.6y)A.4S=$j(A.G.6y);if(A.1h&&!A.1h.J)A.1h=12;if(A.1d&&!A.1d.J)A.1d=12;if(!A.1h&&A.1d)A.1h=A.1d.fO(\'37\');if(A.1h){if(!A.1d)A.1d=A.1h.90(\'8U\');if(!A.33)A.33=A.1h.90(\'1L\');if(!A.33)A.33=A.1h.2t(\'cC:fC\');if(!A.3C)A.3C=A.1h.90(\'8Z\')}if(A.1d&&!A.1d.J)A.1d=12;if(A.33&&!A.33.J)A.33=12;if(A.3C&&!A.3C.J)A.3C=12;if(A.4k&&!A.4k.J)A.4k=12;if(A.35&&!A.35.J)A.35=12;if(A.4S&&!A.4S.J)A.4S=12;if(A.1d&&A.33)A.4s=K};A.6n=B(){A.2S()};A.3y=B(){A.2G()};A.bR=B(1C){A.1C=1C;A.2S()},A.2S=B(){if(!A.4s)E;if(A.8G)E;A.8G=K;A.cJ();F W=A;F N=A.3v();$1R({U:N,1j:\'X\',2O:B(o){W.ca(o)},2d:V})};A.ca=B(X){F W=A;A.1J=1b(X)?X:$34.4Y(X);A.1a=A.1J.2y(\'F\');A.4X=A.1J.8Y(A.G.cc);F 1q=A.1a.v(\'1l\');A.cL();if(1q==\'2z\'){F fN=A.G.8O?A.G.8O.1x(\',\'):[];A.1d.1V(\'\');A.4X.2E();P(F i=1;i<=A.4X.8E();i++){F 1s=A.4X.2y();F bS=(i+1)%2+1;1s.1y(\'fK\',bS);1s.1y(\'gv\',i);1s.bz(A.G.8O);if(A.G.bB)1s.1y(\'bB\',1Z(\'/3r/36/70.8J\',\'bA\',\'[1P:bv]\'));if(A.G.70)1s.1y(\'70.U\',1Z(\'/3r/36/70.8J\',\'bA\',\'[1P:bv]\'));if(A.G.4G)1s=A.G.4G.4I(A,1s);if(A.4G)1s=A.4G(1s);F 2o=A.8H(1s);F 3Q=$(2o).8N(A.1d);if(A.G.3p)A.G.3p.4I(A,3Q,1s);if(A.3p)A.3p(3Q,1s);A.4X.6o()}if(A.4X.8E()<1){F 2o=A.3C?A.3C.1V():\'\';if(!2o){F 5N=A.1d.8D(\'8C\');if(5N){if(5N==\'K\')5N=\'暂无记录\';2o=\'<37 5x="gw \'+(A.1d.8D(\'8C-2e\')||\'gu\')+\' \'+(A.1d.8D(\'8C-1l\')||\'5G\')+\'"><p><em></em><i>\'+5N+\'</i></p></37>\'}}if(2o){F bD=$(2o);if(A.1d.is(\'gA\')&&bD.2t(\'1t > 1i\').J<1){2o=\'<1t><1i 4O="\'+A.1h.2t(\'gp gd\').J+\'">\'+2o+\'</1i></1t>\'}A.1d.47(2o)}}if(A.G.73)A.G.73.4I(A,A.1d,A.1a,A.1J);if(A.73)A.73(A.1d,A.1a,A.1J);A.3Y(A.1d);if(A.4k){1T.5Z.4T(A.1a,A.4k,B(1C){W.bR(1C)},{fy:K,eO:K});A.3Y(A.4k)}if(A.cS)A.cS(A.1a)}R{if(!1q)1q=\'!eB\';F 7f=A.1a.v(\'2H\');if(!7f)7f=\'[\'+1q+\']\';1T.eF(\'5G\',7f,K);if(A.G.cy)A.G.cy(1q)}A.8G=V};A.8H=B(1s){F C=\'\';C=A.33.1V();C=A.cu($(C),1s).fn();if(A.4K)C=A.4K($(C),1s).1V();C=$8M.4G(C,1s);E C};A.fq=B(){};A.cu=B(3Q,1s){F W=A;3Q.2t(\'[38-4K]\').5C(B(){F jo=$(A);F 1p=r(jo.1F(\'38-4K\'),\'{2C}\',1s.v(jo.1F(\'38-4K-1D\')));W.cB(3Q,jo,1p)});E 3Q};A.cB=B(3Q,jo,1p){F 2o=A.1h.2t(\'cC[38-4K="\'+1p+\'"]\').1V();if(2o)jo.47(2o)};A.3Y=B(jo){if(3a.3Y)3a.3Y(jo);if(A.G.3Y)A.G.3Y(jo)};A.cJ=B(){if(1T.3W)1T.3W.cK();if(A.35){if(!A.35.8K(\'.6y\')){A.35.1V(A.8I())}A.35.5J();A.1d.1V(\'\')}R{A.1d.1V(A.8I())}};A.cL=B(){if(1T.3W)1T.3W.cM();A.1d.1V(\'\');if(A.35)A.35.6A()};A.8I=B(){F C=\'\';if(A.4S)C=A.4S.1V();if(!C)C=\'<3B 5x="6y"><fe 50="/3r/6J/2G/fc.8J" /></3B>\';E C}};',62,1264,'||||||||||||||||||||||||||||||||||||this|function|re||return|var|opt|||length|true|break||_url|case|for|_data|else||new|url|false|that|xml||VDCS||_p|null||xcml|func|push|node||_x|treeVar|iso|name|jcont|_msg|rea|_i|jwrap|td|value|replace|status|Date|_row|oj|selector|_status|dir|treeItem|tr|params|typeof|p2|split|addItem|tips|catch|_Dom|page|field|s1|attr|try|_count|values|maps|date|tpl|dcs|_isObject|unit|item|ardata|ajax|treeo|ui|str|html|_value|_v|reTree|rd||_d|width|indexOf||channel|Array||time|_list|fields|ox|_name|error|type|p1|toConvert|len|ise|form|px|toString|height|tg|_html|ary|_n|jformitem|strer|find|jform|test|idx|jfield|getItemTree|succeed|_col|mi|val|undefined|begin|tree|load|message|jsubmit|copy|text|pages|body|sRow|ready|nuller|link|callback|parse|switch|_k|send|codes|alert|utilTree|_a||join|s2|jtpl|util|jloader|ua|div|data||app|init|wid|Object|toi|1000|arguments|Math|input|getItem|substr|oTable|_ObjectType|ars|getObjectType|bind|table|images|len2|add|ps|getURL|_days|utilTable|reset|inp|submit|span|jtple|tl|_ischeck|action|timer|default|pattern|_NodeItemNow|agent|syb|script|_NodeItemLength|od|isInt|jitem|extend|disabled|events|key|exec|progressi|num|uio|_nodes||fielda|async|isTree|reTable|utilXCML||append||tmpAry|hour|err|get|parseInt|object|_field|_var|css|day|method|jpaging|tmpItemArray|serve|serveVar|on|local|toReplaceRegex|submitSet|isinit|getMessage|_Data|toHTML|opt2|href|diff|getValue|scroll|_r|apd|cookie|setValue|include|filterItem|fmt|call|_queue|refill|substring|isa|RegExp|colspan|v2|ver|string|jloading|parser|getItemValue|obj|txt|tableList|toMapByXML|count|src|sMode|screen|configure|style|minute|wrap||BR|query|ConfigureField|getConfigure|alen|jsono|lng|dbg|responseText|_dt|_ary|browser|responseXML|left|reMap|getCount|utilMap|doItemBegin|doItemMove|_opt|loadXML|getDomObject|sTit|ActiveXObject|loadResponseXML|class|_NodeItem|_node|url_back|end|each|toPaddedString|vars|iev|info|serveURL|http|show|themes|min|_timer|_empty|alt|fname|submit_status|interval|querys|_send|no|_id|x1|getTime|pageNum|paging||formCheck|jtips|version|already|isf|_y|toEncode|XCML_NODENAME|times|embed|getNodeTree|options|getNodeObject|title|setFields|EXT|PATTERN_VAR|exp|tmpRow|keyCode|_key|ton|refresh|move|getElementsByTagName|toDate|scrollTop|dotPos|doParse|PATTERN_PRE|points|deep|now|loading|tmpCol|hide|resize|extendo|emoney|location|doBegin|ConfigureNode|box|getItemCount|common|linktop|strc|event|_isa|addParam|urlBack|align|_func|_vcheck|target|charset|_page|_isload|items|tim|pairs|avatar|rc|or|binds|focus|_date|ret|round|charCodeAt|prefix|epr|jforms|position|backurl|getArray|_message|getHours|toTableByXML|isData|lt|millisecond|nodes|match|setItem|charAt|xmlFooter|_blank|rel|toNumber|xmlHeader|isint|setTime|isNum|iss|parseFloat|toXML|hidden|color|zA|total|rgx|slen|overflow|right|getDate|today|max|timec|isn|getFullYear|random|opacity|extractJsoni|Z0|pcn|getItemKey|second|getSeconds|month|getMonth|rRE|datey|getMinutes|year|doAppendTree||dates|doMove|isLogin|msg|ws|bindEvent|scrollLeft|isTable|initer|ing|XMLHTTP|arys|goback|uri|isdebug|xmlHttp|hint|submit_value|statusMessage|forms|statusOpt|statusParser|def|open|getXMLHttpObject|of|content|serv_vars|toReplaceVar|frm|mini|vers|sDate|months|empty|attrd|row|scriptd|isparse|getItemString|getLoadString|gif|finder|Conversion|dtml|appendTo|jsonFields|chrome|navigator|safari|msie|_back|cont|file|rewrite|ext|getItemTable|tple|finde|map|hei|getNodeValue|router|number|_NodeConfigure|com|childNodes|filter|_isexec|getConfigureField|shockwave|application|VCheck|put|vals|afield|linkURL|_e|req|formCheckExtend|firstChild|implementation|tableData|aryObj|www|issend|doParseNode|nodeValue|execItem|middle|macromedia|flash|doParseItem|eString|debug|urlmode|365|urlMode|getflashplayer|webkit|opera|firefox|mozilla|display|base|setExt|index|relative|top|account|instanceof|isValid|100|0123456789|boolean|ta|toJSON|3600|yyyy|Lnegth|mm|ss|86400|weekdays|True|prototype|block|d_1|passport|upload||NaN|NEWLINE|d_2|support|lang|May|60000|emotes|editor|STime||referrer|oo|mode|_smt|_sbt||MSX|_rst|toJS|setURL|border|_Top|write|getVars|ins|bindi|ne|POST|xff|x00|oxml|console|126|_ready|readyState|realtime|CHARSET|vrt|overrideMimeType|mime|_Left|toCuted|dirn|yes|initURL|cssText|x2|floor|domain|qi|prober|unescape|pluginspage|_initURL|tmpExpires|while|XMLHttpRequest|toFlash|clearInterval|apos|head|sidebar|quot|clearTimeout|000|wh|padding|5px|scrollHeight|wi|200|toDecodei|submitInit|PATTERN_LEBEL_ITEMS|toEncodeFilterValue|toReplaceEncodeFilter|s5||__initer|s3|names|uuid|DTML|getConfigureNode|array|extractJson|uid|user_avatar|toReplacePre|jempty|tips_timer|itip|PATTERN_FLAG_LABEL|submit_succeed|click|submit_ing|tips_speed|Error|tagName|message_|dat|setVar|filterURL|clickPage|oe|_NodeItemArray|list|getFields|getI|isItem|parserAsync|opts|code|getFieldArray|parserTips|callerror|back|error_message|tips_succeed|delItem|post|Microsoft|parseAsync|onerror|node_table|resolveExternals|slice|getXML|submitLock|getValues|getServeURL|serv_method||loader|check|getValueArray|createDocument|vmin|tips_cover|sendSucceed|Page|isSucceed|refille|sendAsync|isiniter|md5|fails|isCheck|_selector|refillei|xmp|encrypt|tips_status|atts|tit|encrypt_timer|encrypt_field|loadon|start|loadoff|done|servURL|URL|keypress|_min|toTreeByXML|parsePaging|autoenter|tips_type|isInputInt|directories|menubar|scrollbars|window|confine|||tips_msg|placeholder|pos|SELECT_INPUT_NAME|tip|addClass|bindChange|toolbar|removeClass||||blur|toState|states|onReady|onError|onLoad|lastIndexOf|off||error_unknown|message_succeed|confirm|message_back|GET||debug_xml|Msxml2|linkPlace|resizable|Msxml|300|toUpperCase|getXMLHttp|document|debugxml||innerWidth|tip_class|0px|doSubmit|history|reload|root|gotop|setInterval|which|noEnter|doFocus|appendChild|protocol|external|formHide|element|_o|addFav|parent|servVar|jquery|addPanel|fav|_initer|ctrlKey|innerHeight|speed|altKey|_placeholder|addFavorite|toConvertInput|doSubmitOnce|submitQuick|Height|prompt|jbody|formcheck|submitOnce|setTimeout|timeout|isClickReturn||||pageYOffset|cover|pageXOffset|compatMode|strl||sl|isPrototypeOf|sr||strr|o2s|inps|XCML|rs|toInt|toBool|popups|toNum|rv|trim|s2o|sh|Dec|Nov|Oct|jump|January|March|February|Sep|Aug|Feb|Jan|Mar|Apr|Jul|Jun|tob|too|Try|apply|initialize|604800000|86400000|toFormat|3600000|create|Class|continue|bar|nodeType|img|addEvent|clone|getWeekDay|getDay|isnum|tn|isInte|isNume|outerHTML|isNumber|Hash|pagingParse|distances|distance|isun|isb|ish|Function|April|limit|setFile|getDir|setDir|first|nav|toLowerCase|userAgent|IMG_PATH|setUA|_base|theme|_oe_|setUnit|setr|jsonFielda|parents|appVersion|close|MinorVer|MajorVer|ieo|ie6|ie8|ie7|MSIE|openDatabase|message_parser|w3c|CSS1Compat|gecko|MessageEvent|setServeVar|setPath|EXTS|strength|money|price|prestige|sTime|config|th|coin|December|July|June||August|September|November|October|php||thead|_t|unknown|setv||inline|_sn_|iblank|UNKNOWN|gray|utf|tbody|CONTENT_TYPE|CONTENT_TYPE_XML|EMPTY_REPLACERS|EMPTY_REPLACER|popup|message_serve|toXMLMap|model|to_|filter_|explain|remark|getLength|doEnd|leni|getItemInt|u0000|u00ff|concat|cut|toTXT|amp|PATTERN_LEBEL_DATS|PATTERN_LEBEL_DATA|PATTERN_LEBEL_DAT|getRandNum|PATTERN_LEBEL_VARS|getRandom|PATTERN_LEBEL_DATAS|toCommaK|toTemplat|toFilterText|s4|toPrice|PATTERN_LEBEL_ITEM|vi|queryn|iend|imove|10px|backgroundColor|doItemEnd|||FFF|setItemValue|margin|auto|getItemValueInt|008080|font|bold|weight||ibegin|line|prepend|zIndex|vn|log|queryi|getItemNum|message_error_unknown|fixed|getCol|col|radius|getRow|JSON|PATTERN_LEBEL_VAR|PATTERN_VAR_PX|newTree|valueOf|toDateDiff|360000|toMinuteString|toStringQuick|newTable|newMap|toDateAdd|XCML_NODE_NAME_CONFIGURE|isXCML|ms|newXCML|isMap|nn|paging_num|encodeURI|ftp|toEncodei|encodeURIComponent|hlink|linkr|toDecode|regEx|pg|paging_url|decodeURI|decodeURIComponent||||||isSafe||XCML_NODE_NAMES||__node|toGMTString||PATTERN_FLAG_PARAMQ|PATTERN_FLAG_PARAMS|path|escape||PATTERN_FLAG_PARAM|expires||PATTERN_FLAG_CONTENT|PATTERN_FLAG_OPTION|PATTERN_FLAG_STR|week|PATTERN_FLAG_CONTENT2|3650|PATTERN_FLAG_LABELS|_|toTreeByString|toMap|NodeValue|XCML_NODE_CONNECT_SYMBOL|XCML_NODE_EXIST|__yes|getNow|BATCH_SYMBOL|PATTERN_FLAG_VAR|getValueGipId|PATTERN_FLAG|SWAP_SYMBOL|getToday|ivi|bottom|classid|elementv|clsid|D27CDB6E|11cf|AE6D|flashvars|minlength|movie|radio|setLinkURL|quality|high|96B8|codebase|cellspacing|cellpadding|absolute|wmode|resete|opaque|select|textarea|pub|download|cabs|swflash|cab|addVar|param|nopermission||failed|setRequestHeader|Content||urlencoded|Type|tips_status_|onreadystatechange|304|message_formcheck|frm_post|nError|nResult|noexist|nodata|QueuesLoader|Missing|_encrypt_timer|doPop|Flash|checked|Send|tmapsByXML|statusSucceed|error_msg|Queues|QueueAsync|set|parentNode|444553540000|getItemNow|background|cdeaf6|ownerDocument|Row|Col|loadURL|DOMParser|loadFile|getItemLength|pointer|doShow|parseFromString|cursor|oString|XMLDOM|getItemField|otable|moz||documentElement|20px|api|doInit|ivn|isObject|getItemArray|getItemValueNum|attri|otree|alpha'.split('|'),0,{}))

extendo($v,{
	'date.unit'		:['秒','分钟','小时','天'],
	'date.weekdays'		:['星期','天一二三四五六'],
	'date.months'		:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
	'date.distances'	:['$1秒前','$1分钟前','$1小时前','$1天前'],
	'ajax.states'		:['正在初始化...','正在发送请求...','正在接收数据...','正在解析数据...','数据请求结束！']
});

extendo($lang,{
	'pages':{
		'fav.prompt'		:'请按快捷键 "Ctrl+D" 添加收藏！',
		'click'			:['$1','您确定$1吗？','您确定要$1吗？\n\n执行该操作后将不可恢复！']
	}
});

/*
Version:	VDCS UI Library
Support:	http://go.hpns.cn/vdcs/js
Uodated:	2014-01-00
*/

var ui={speed:500,effect:{},
	show:function(jo,real){return jo.ishow(real)},hide:function(jo,real){return jo.ihide(real)},
	float:function(jo,opt){return jo.floato(opt)},
	floatBox:function(jo,opt){if(!iso(jo)) jo=$(jo).appendTo('body');return jo.floatBox(opt)},
	
	//lock
	lock:{items:{},
		k:function(k){return k||'_def_'},
		is:function(k,set){
			var re=this.items[this.k(k)];
			if(set) this.en(k);
			return re
		},
		en:function(k){this.items[this.k(k)]=true},
		un:function(k){this.items[this.k(k)]=false},
	'':''},

	initor:function(node){
		if(this[node] && this[node].initer) this[node].initer();
	},
'':''};

extendo(ui,{		//oparent:ui,
	//cover
	cover:{id_cover:'__xtip_cover',
		show:function(opt){
			opt=ox({real:false},opt);
			if(this._show&&!opt.real)return;this._show=true;
			this.real=opt.real;
			var that=this;
			var _resize=function(){
				if(!that._show)return;
				that.jo.width($w.wi).height($w.hi)
			};
			if(!this._init){
				this.jo=$('<div></div>').attr('id',this.id_cover).addClass('xtip_cover')
						.css({zIndex:99,position:'fixed',top:0,left:0,backgroundColor:'#000',opacity:0.8,display:'none'})
						.appendTo('body');
				//this.jo.fixIE6();
				this._init=true
			}
			this.jo.fadeIn(ui.speed);
			_resize();$w.resize(function(){_resize()});
			$w.reset();
			return this.jo
		},
		hide:function(){
			if(!this._show)return;
			this.jo.fadeOut(ui.speed);
			this._show=false;this.real=false
		},
		hidei:function(){if(!this.real) this.hide()},
	'':''},
	
	//mini
	mini:{id_mini:'__xtip_mini',
		show:function(message,opt){
			opt=ox({timer:2},opt);
			if(!this.jo){
				this.jo=$('<div></div>').attr('id',this.id_mini).addClass('xtip_mini')
						.css({zIndex:901,position:'fixed',top:45,right:15,		//.css(width,150)
							color:'#FFF',backgroundColor:'#68AF02',textAlign:'center',padding:'3px 10px'})
						.appendTo('body');
				//this.jo.fixIE6();
				this.jo.html('loading..');
			}
			if(opt.color) this.jo.css('color',opt.color);
			if(opt.bgcolor) this.jo.css('background-color',opt.bgcolor);
			if(opt.css) this.jo.css(opt.css);
			if(message) this.jo.html(message);
			ui.show(this.jo,'downi');
			if(opt.timer) this.hide(opt.timer);
			return this.jo
		},
		message:function(message){
			if(this.jo) this.jo.html(message)
		},
		hide:function(timer){
			var that=this;
			$w.timeout(function(){ui.hide(that.jo,'upi')},timer)
		},
	'':''},
	
	//popup
	popups:function(opt,message,cover,timer,close){
		if(!iso(opt)){
			opt={status:opt,message:message};
			if(!isn(cover)) opt.cover=cover;
			if(!isn(timer)) opt.timer=timer;
			if(!isn(close)) opt.close=close;
		}
		return this.popup.show(opt)
	},
	popup:{id_:'__xtip_popup',_classname:'ui-popup',speed:500,timer:2,
		init:function(){
			if(this.isinit)return;this.isinit=true;
		},
		bar:function(opt){
			
		},
		show:function(opt){
			this.init();
			if(this._show)return;this._show=true;
			opt=ox({status:'',message:'hello',cover:true,timer:this.timer,classname:this._classname},opt);
			this._opt=opt;
			var that=this;
			var _resize=function(){
				if(!that._show)return;
				that.jo.css({top:($w.hi-that.jo.height()-100)/2,left:($w.wi-that.jo.width())/2});
			};
			if(!this.init_){
				this.jo=$('<div></div>').attr('id',this.id_).addClass(this._opt.classname)
						.css({zIndex:10002,position:'fixed',top:0,left:0})
						.appendTo('body');
				//this.jo.fixIE6();
				$('<div></div>').addClass('pinner').append(
					$('<div></div>').addClass('pbody').append($('<p><em></em><span></span></p>'))
				).appendTo(this.jo);
				this.jcont=this.jo.finder('p:first span');
				if(!this.jcont) return;
				this.jo.click(function(){that.click()});
				this.init_=true;
			}
			this.jo.removeClass().addClass(this._opt.classname);
			if(this._opt.status) this.jo.addClass(this._opt.status);
			ui.show(this.jo,'up');
			if(this._opt.cover) ui.cover.show({real:true});
			if($b.ie6) this.jo.width(this._opt.message.length*10+120);		// ie6 hack
			this.message(this._opt.message);
			_resize();$w.resize(function(){_resize()});
			clearTimeout(this.timer_o);
			this.timer_v=this._opt.timer;
			if(this.timer_v) this.timer_o=$w.timeout(function(){that.hide()},this.timer_v);
			return this.jo
		},
		message:function(message){
			if(this._show&&this.jcont) this.jcont.html(message)
		},
		click:function(){if(this.timer_v) this.hide()},
		hide:function(){
			if(!this._show)return;
			ui.hide(this.jo,'up');
			if(this._opt.cover) ui.cover.hide();
			if(this._opt.close) this._opt.close();
			this._show=false
		},
	'':''},
	
	hint:function(jo,msg,opt){
		opt=ox({status:'',callback:null,tip:'.itip',tip_class:'itip',timer:2,speed:350,cover:true},opt);
		if(!jo||!jo.length){
			if(msg=='hide') ui.popup.hide(true)
			else ui.popups(opt.status,msg,opt.cover,opt.timer)
			return
		}
		if(msg=='hide' && this.hintTimer){
			$w.clearTimeout(this.hintTimer);this.hintTimer=null;
			//this.hintj&&this.hintj.slideUp()
		}
		if(msg=='hide') return;
		if(!msg || msg=='data') msg=jo.find('span').attr('data-value');
		if(!msg) return;
		msg=r(msg,'\n','<br/>');msg=r(msg,';','<br/>');msg=r(msg,'$$$','<br/>');
		if(isdebug('hint')) dbg.t(opt.status+' = '+msg);
		if(opt.tip) jo.find(opt.tip).removeClass().addClass(opt.tip_class).addClass(opt.status);
		//jo.hide();
		jo.find('span').html(msg);
		jo.slideDown(opt.speed);
		if(opt.timer!=null){
			if(isn(opt.timer)) opt.timer=2;
			var that=this;
			this.hintj=jo;
			this.hintTimer=$w.timeout(function(){
				//jo.stop().slideUp(opt.speed);
				jo.stop().slideUp(100);
				if(isf(opt.callback)) opt.callback();
				that.hintj=null
			},opt.timer);
		}
	},
	
	tipload:function(jo,action){
		var cn_cont='tipload-container';
		if(jo.hasClass(cn_cont)){
			if(action=='remove'){
				jo.find('.tipload-shade').remove();
				jo.find('.tipload').remove();
				jo.removeClass(cn_cont)
			}
			return
		}
		jo.addClass(cn_cont);
		jo.append('<span class="tipload-shade"></span><span class="tipload"><em></em></span></a>')
	},
	
	confirm:function(msg,callback,opt){
		if(msg && callback){
			opt=ox({message:msg,callback:callback},opt);
		}
		else{
			opt=msg;
		}
		opt=ox({mode:0,status:'info',title:'操作确认',submit_name:'确定',close_name:'取消'},opt);
		if(opt.mode>0) opt.message='您确定 '+opt.message+' 嘛？';
		if(opt.mode>1) opt.message+='<br/>操作将可能无法恢复！';
		//dbg.o(opt);
		var htmla=[];
		htmla.push('<div>');
		htmla.push('<div class="iconfirm">');
		if(opt.status) htmla.push('<p class="itip m info ac"><em></em><span>');
		htmla.push(opt.message);
		if(opt.status) htmla.push('</span></p>');
		htmla.push('</div>');
		htmla.push('</div>');
		var opti={title:opt.title,nobar:opt.nobar,nobtn:opt.nobtn,onsubmit:opt.callback,submit_name:opt.submit_name,onclose:opt.onclose,close_off:opt.close_off,close_name:opt.close_name,cover:opt.cover};
		this.jconfirm=$(htmla.join(NEWLINE)).ibox(opti)
	},
	
	mtip:function(opt){
		opt=ox({wrap:null,status:'info',title:'系统提示',message:'提示详细信息'},opt);
		opt.jwrap=opt.wrap?$j(opt.wrap):$('form!last').parent();
		this.jmtip=opt.jwrap.finder('.mtip');
		if(!this.jmtip){
			var htmla=[];
			htmla.push('<div class="mtip">');
			htmla.push('	<cite class="info"><em></em></cite>');
			htmla.push('	<h3>提示标题</h3>');
			htmla.push('	<h4>提示内容</h4>');
			htmla.push('	<h5>');
			htmla.push('		<a class="btn" el="index" href="'+$c.url('root')+'"><span>首页</span></a>');
			htmla.push('		<a class="btn" el="back" href="javascript:;"><span>返回</span></a>');
			htmla.push('	</h5>');
			htmla.push('</div>');
			this.jmtip=$(htmla.join(NEWLINE)).appendTo(opt.jwrap);
		}
		this.jmtip.show();
		this.jmtip.find('cite').removeClass().addClass(opt.status);
		this.jmtip.find('h3').html(opt.title);
		if(isa(opt.message)) opt.message='<p>'+opt.message.join('</p><p>')+'</p>';
		this.jmtip.find('h4').html(opt.message);
		var jbtn=this.jmtip.find('h5');
		if(!opt.onback && !opt.back_link) opt.onback=function(){$p.goback()};
		ui.setbtn('index',jbtn,opt);ui.setbtn('back',jbtn,opt);
		this.jmtip.opt=opt;
		return this.jmtip
	},
	
	setbtn:function(el,jbtn,opt){
		var jbtni=jbtn.find('[el="'+el+'"]');
		if(opt[el+'_off']) jbtni.hide();
		if(opt[el+'_name']) jbtni.find('span').html(opt[el+'_name']);
		if(opt[el+'_link']) jbtni.attr('href',opt[el+'_link']);
		if(opt['on'+el]) jbtni.click(opt['on'+el]);
	},
'':''});


/* ################ box ################ */
extendo(ui,{		//oparent:ui,
	box:function(jo,opt){$j(jo).ibox(opt)},
'':''});
extendo(ui.box,{
	createWrapper:function(jo,opt){
		opt=ox({cover:true},opt);
		var content=iso(jo)?jo.html():jo;
		//alert(content);
		var htmla=[];
		htmla.push('<div class="ui-box rc5">');
		htmla.push('<h3><t></t><a class="close iclose"><span>close</span></a></h3>');
		htmla.push('<div class="conts">'+content+'</div>');
		htmla.push('<h5><a class="btn m submit" el="submit"><span>确定</span></a><a class="btn close" el="close"><span>取消</span></a></h5>');
		htmla.push('</div>');
		return ui.floatBox(htmla.join(NEWLINE),opt)
	},
	setWrapper:function(jbox,opt){
		var jbtn=jbox.find('h5');
		if(opt.nobar) jbox.find('h3').hide();
		if(opt.nobtn) jbtn.hide();
		if(opt.title) jbox.find('h3 t').html(opt.title);
		if(opt.btn_align) jbtn.css('text-align',opt.btn_align);
		ui.setbtn('submit',jbtn,ox(opt,{onsubmit:null}));
		ui.setbtn('close',jbtn,ox(opt,{onclose:null}));
		var _submit=function(){
			var issubmit=true;
			if(opt.onsubmit) issubmit=opt.onsubmit(jbox);
			if(typeof issubmit =='undefined') issubmit=true;
			if(issubmit) _close();
		};
		var _close=function(){
			var isclose=true;
			if(opt.onclose) isclose=opt.onclose(jbox);
			if(typeof isclose =='undefined') isclose=true;
			if(isclose){
				ui.hide(jbox,opt.effect);
				if(opt.cover) ui.cover.hidei();
				jbox.remove()
			}
		};
		jbox.find('h3 > .close, h5 > .close').click(_close);
		jbox.jsubmit=jbox.find('h5 > .submit').click(_submit);
	},
	submit:function(jbox){jbox.find('h5 > .submit').click()},
	close:function(jbox){jbox.find('h3 > .close').click()},
'':''});

(function($){
$.fn.extend({
	ibox:function(opt){
		if(opt && !iso(opt)){
			switch(opt){
				case 'submit':		ui.box.submit(ui.jibox);break;
				case 'close':		ui.box.close(ui.jibox);break;
			}
			return
		}
		var jthis=$(this);
		opt=ox({effect:'left',cover:true},opt);
		opt=ox(opt,jthis.data());
		//if(ui.jibox) ui.jibox.stop(true,true).remove();
		var jbox=ui.box.createWrapper(jthis,opt);
		ui.box.setWrapper(jbox,opt);
		ui.jibox=jbox;
		if(opt.bindEvent) opt.bindEvent(jbox);
		return jbox
	},
'':''});
})(jQuery);


/* ############### effect ############## */
extendo(ui.effect,{
	remove:function(jo,callback){
		jo.fadeOut(300,function(){
			jo.remove();
			if(callback) callback();
		});
	},
	expandTalk:function(jmsg,jact,opt){
		opt=ox({style:'def',speed:100,speedh:200,timer:0.5,high_base:32,high_multiple:2,collapse:false},opt);
		var jmsgp=jmsg.parent(),msgp_h=jmsgp.height()||opt.high_base,msgp_origh=msgp_h*opt.high_multiple,timer_pack;
		jmsgp.attr('height_orig',msgp_origh);
		jmsg.autoresizer({speed:opt.speed,speedh:opt.speedh});
		jmsg.click(function(){
			if(opt.style=='high' && !jmsg.val()){
				jmsgp.animate({height:msgp_origh},{speed:opt.speed});
			}
			if(!jact.is(':visible')) jact.slideDown(opt.speed)
		});
		if(opt.collapse){jmsg.blur(function(){
			timer_pack=$w.timeout(function(){
				if(jmsg.val())return;
				if(opt.style=='high') jmsgp.animate({height:msgp_h},opt.speedh);
				if(jact.is(':visible')) jact.slideUp(opt.speedh);
			},opt.timer);
		});}
		if(jmsg.val()) jmsg.focusEnd().click();
		jact.click(function(){$w.clearTimeout(timer_pack)});
	},
	counts:function(jbox,opt){
		opt=ox({max:140},opt);
		var jbox=jbox;
		var jmsg=jbox.find('textarea[name="message"]');
		var keyupBox=function(){
			var re=false;
			var _count=opt.max-jmsg.val().length;
			jbox.finde('letters').text(_count);
			jbox.find('.counts i').text(_count);
		};
		jmsg.keyup(keyupBox);
		keyupBox()
	},
'':''});


/* ################ form ############### */
ui.form={

	init:function(jwrap){
		var that=this;
		if(!jwrap) jwrap=$('body');
		if(jwrap){
			this.bindi(jwrap);
			jwrap.find('[data-ui-form]').each(function(){
				var jthis=$(this);
				if(jthis.attr('data-ui-form')) that.opnParser(jthis,{type:jthis.attr('data-ui-form'),value:jthis.attr('data-value')});
			});
		}
		this.opnInit()
	},

	opn_class_pop:'gray',
	opnInit:function(){
		var that=this;
		$(document).on('click.button.data-api', '[data-toggle^=button]', function(e){
			var $opn = $(e.target);
			if (!$opn.hasClass('opn')) $opn = $opn.closest('.opn');
			that.opnSwitch($opn,'toggle');
		})
	},
	opnSwitch:function(obj,option){
		var that=this;
		return obj.each(function(){that.opnToggle(obj)})
	},
	opnToggle:function(obj){
		var that=this;
		var $parent = obj.closest('[data-toggle="buttons-radio"]')
		$parent && $parent.find('.opn-'+that.opn_class_pop+'').removeClass('opn-'+that.opn_class_pop+'');
		obj.toggleClass('opn-'+that.opn_class_pop+'')
	},
	
	opnParser:function(jo,opt){
		if(typeof opt !='object'){
			this.type=opt;
		}else{
			this.type=opt.type;
		} 
		if(!opt.type) opt.type='checkbox';
		if(!isun(opt.value)) jo.find(':input').vals(opt.value);
		if(jo.find('input').length>8) return;
		var opn_html=this.getOpnHtml(jo,opt);
		jo.after(opn_html);
		this.opnBind(jo,opt);
		jo.hide();
	},
	getOpnHtml:function(jo,opt){
		var that=this;
		var htmla=[];
		htmla.push('<div class="opn-group" data-toggle="buttons-'+opt.type+'">');
		jo.find('input').each(function(){
			var jthis=$(this);
			var id=jthis.attr('id');
			var text=jthis.parent('label').text();
			if(!text) text=jthis.next('span').text();
			if(!text) text=jthis.attr('value');
			var ischecked='';
			if(jthis.checked()) ischecked='opn-'+that.opn_class_pop+'';
			htmla.push('<a type="button" class="opn '+ischecked+'">'+text+'</a>');
		});
		htmla.push('</div>');
		//$('.myval').after(htmla.join(''));
		return htmla.join('')
	},
	opnBind:function(jo,opt){
		var that=this;
		jo.next('.opn-group').find('.opn').each(function(i){
			if($(this).attr('isinit')) return;
			if(that.type=='checkbox'){
				$(this).bind('click.check',function(){
					if($(this).hasClass('opn-'+that.opn_class_pop+'')){
						that.getOpnOrigWrap($(this)).find('input:eq('+i+')').checked(false);
					}else{
						that.getOpnOrigWrap($(this)).find('input:eq('+i+')').checked(true);
					}
				}).attr('isinit','yes');
			}else if(that.type=='radio'){
				//that.getOpnOrigWrap($(this)).find('input').checked(false)
				$(this).bind('click.check',function(){
					var jinput=that.getOpnOrigWrap($(this)).find('input');
					jinput.checked(false);
					jinput.end().find('input:eq('+i+')').checked(true);
					//alert(i);
				}).attr('isinit','yes');
			}
			
		});
	},
	getOpnOrigWrap:function(jthis){
		var jparent=jthis.parent('.opn-group');
		var jre=jparent.prev('[data-ui-form]');
		if(jre.length<1) jre.prev('div');
		return jre
	},


	bindi:function(jo){
		var selector='input[type="checkbox"][data-bind="check"]';		//,.iradio,.iselect
		jo=jo?jo.find(selector):$(selector);
		this.bindii(jo)
	},
	bindii:function(jo){
		var that=this;
		jo.each(function(){
			var jthis=$(this),type=jthis.attr('type');
			switch(type){
				case 'checkbox':that.bindicheck(jthis);break;
				//case 'iradio':that.bindiradio(jthis);break;
				//case 'iselect':that.bindiselect(jthis);break;
			}
		});
	},
	bindicheck:function(jo){
		if(!jo.is('input') || !jo.attr('type')=='checkbox') return;
		if(jo.attr('_init'))return;jo.attr('_init','yes');
		var jlabel=jo.parent();
		if(!jlabel.is('label')){
			jlabel=$('<label>'+jo.outerHTML()+'</label>');
			jo.replaceWith(jlabel);
			jo=jlabel.find('input')
		}
		var jem=jlabel.finder('em');
		if(!jem){
			jem=$('<em class="icheck"></em>');
			jem.addClass(jo.attrd('style'));
			jo.after(jem)
		}
		jo.hide();
		if(isdebug('data')) dbg.t('icheck','value='+jo.val());
		if(jo.checked()) jem.addClass('checked');
		var _parse=function(){
			if(jo.checked()){
				jo.checked(false);
				jem.removeClass('checked')
			}
			else{
				jo.checked(true);
				jem.addClass('checked')
			}
			return false
		};
		jlabel.click(_parse)
	},
	bindiradio:function(jo){},
	bindiselect:function(jo){},
'':''};


/* ############### serve ############### */
ui.serve=new VDCS.serve();


/* ################ list ############### */
ui.list=new VDCS.list();

ui.paging={
	parser:function(treeVar,jpaging,click,opt){
		opt=ox({},opt);
		/*
		var opt_str={};
		if(typeof opt.limit !='undefined') opt_str.limit=opt.limit;
		*/
		jpaging.html('');
		if(treeVar.vi('paging.total')<1) return;
		jpaging.html(this.toString(opt,treeVar));
		jpaging.find('a[page]').click(function(){
			var jthis=$(this);
			if(ins(jthis.parent('li').attr('class'),'disabled')<0){
				var page=$(this).attr('page');
				click && click(page)
			}
			return false
		});
		jpaging.find('.jump_btn').click(function(){
			var page=toi(jpaging.find('.jump_page').val());
			if(page) click && click(page)
			return false
		});
	},
	filterOpt:function(opt){
		var numhalf=Math.floor(opt.pagenum/2);
		opt.pagebegin=opt.page-numhalf;
		if(opt.pagebegin<1) opt.pagebegin=1;
		opt.pageend=opt.pagebegin+opt.pagenum-1;
		if(opt.pageend>opt.pagetotal) opt.pageend=opt.pagetotal;
		return opt
	},
	toString:function(opt,treeVar){
		var _opt={page:1,listnum:10,total:0,pagenum:5,pagetotal:0,pagebase:0,
			classname:'pagination',
			href:'javascript:;',
			limit:false,around:false,total:true,jump:false,jump_txt:'GO'};
		if(treeVar && treeVar.vi){
			_opt=ox(_opt,{
				page:treeVar.vi('paging.page'),
				listnum:treeVar.vi('paging.listnum'),
				total:treeVar.vi('paging.total'),
				pagenum:treeVar.vi('paging.pagenum'),
				pagetotal:treeVar.vi('paging.pagetotal'),
				pagebase:treeVar.vi('paging.pagebase')
			});
		}
		opt=ox(_opt,opt);
		opt=this.filterOpt(opt);
		var htmla=[];
		htmla.push('<div class="'+opt.classname+'" data-total="'+opt.total+'" data-pagetotal="'+opt.pagetotal+'">');		// pagination-centered
		htmla.push('<ul>');
		if(opt.limit) htmla.push('<li class="first'+(opt.page<2?' disabled':'')+'"><a href="'+opt.href+'" page="1">&laquo;</a></li>');
		if(opt.around || opt.page>1) htmla.push('<li'+(opt.page<2?' class="disabled"':'')+'><a href="'+opt.href+'" page="'+(opt.page-1)+'"><</a></li>');
		for(var i=opt.pagebegin;i<=opt.pageend;i++){
			var _active='';
			if(opt.page==i) _active=' class="active"';
			htmla.push('<li'+_active+'><a href="'+opt.href+'" page="'+i+'">'+i+'</a></li>');
		};
		if(opt.around || (opt.page<opt.pagetotal && opt.page>0)) htmla.push('<li'+(opt.page>=opt.pagetotal?' class="disabled"':'')+'><a href="'+opt.href+'" page="'+(opt.page+1)+'">></a></li>');
		if(opt.limit) htmla.push('<li class="last'+(opt.page>=opt.pagetotal?' disabled':'')+'"><a href="'+opt.href+'" page="'+opt.pagetotal+'">&raquo;</a></li>');
		htmla.push('</ul>');
		if(opt.total) htmla.push('<cite><i>'+opt.total+'</i></cite>');
		if(opt.jump){
			htmla.push('<div class="jump">');
			htmla.push('<i><input type="text" class="jump_page" name="jump_page" value="'+opt.page+'" size="3" /></i>');
			htmla.push('<a class="btn jump_btn"><span>'+opt.jump_txt+'</span></a>');
			htmla.push('</div>');
		}
		htmla.push('</div>');
		return htmla.join(BR)
	},
'':''};


/* ################ pages ############## */
ui.pages={channel:'',
	ex:function(jbtn,opt){
		var that=this;
		jbtn.click(function(){
			that.recordClick($(this),opt);
			return false
		})
	},
	exClick:function(jbtn,opt){
		opt=ox({names:'标题',name:'操作'},opt);
		var that=this;
		var serveURLE=ui.serve.getURL(ox({channel:this.channel,x:'e'},opt.serveE));
		var serveURLX=opt.serveXurl?opt.serveXurl:ui.serve.getURL(ox({channel:this.channel,x:'x'},opt.serveX));
		//dbg.t('url',serveURLE);
		//dbg.t('url',serveURLX);
		if(!opt.callbacke){		//模板加载完之后调用的函数
			opt.callbacke=function(html){
				that._bindParseX(html,serveURLX,opt);		
			};
		}
		this._bindParseE(serveURLE,opt)
	},
	_bindParseE:function(_url,opt){
		ui.mini.show('正在加载..');
		$ajax({url:_url,value:"text",ready:function(html){if(opt.callbacke) opt.callbacke(html)},error:true})
	},
	_bindParseX:function(html,_url,opt){
		ui.mini.hide();
		
		var xaction=opt.xaction;
		var _submit=function(){
			xaction.parser();
			return false
		};

		var opt_box={onsubmit:function(){return _submit()}};
		opt_box=ox({},opt,opt_box);
		var htmla=html.split('<!--script-->'),_script=htmla[1];
		var jhtml=$(htmla[0]);
		if(!jhtml.finder('.tips')) jhtml.append('<div class="tips hide"><p class="itip"><em></em><span>提示信息</span></p></div>');
		var jbox=jhtml.ibox(opt_box);
		if(_script) $('body').append(_script);
		if(opt.boxInit) opt.boxInit(jbox);
		opt.callbackx=function(status,treeVar){
			jbox.ibox('close');
			if(opt.succeed) opt.succeed(treeVar);
		};

		if(opt.serveXaction) xaction=opt.serveXaction();
		if(!xaction){
			xaction=extend(new VDCS.forms(),{	//app.forms
				initer:function(ps,selector){
					ps=ox({frm:'',
						names			: opt.names,
						message_formcheck	: '请填写必要的信息！',
						message_parser		: opt.name+'中..',
						message_succeed		: opt.name+'成功！',
						submit_ing		: opt.name+'中..',
						submit_succeed		: opt.name+'成功！',
						servURL			: _url},ps);
					this._initer(ps,selector);if(!this.isinit)return;
					this.submitInit();
				},
			'':''});
		}
		xaction.jbody=jbox;
		xaction.initer(ox({goback:false,callback:opt.callbackx},opt.xopt))
	},
	
	record:function(jbtn,opt){return this.ex(jbtn,opt)},
	recordClick:function(jbtn,opt){return this.exClick(jbtn,opt)},
	del:function(jbtn,opt){
		if(!opt.confirm) opt.confirm='您确定要删除一条信息嘛？';
		var that=this;
		jbtn.click(function(){
			ui.confirm(opt.confirm,function(){
				that.delParser($(this),opt);
			})
			return false
		});
	},
	delParser:function(jbtn,opt){
		var _url=ui.serve.getURL(ox({channel:this.channel,x:'x'},opt.serveX));
		//dbg.t(_url);return;
		$ajax({url:_url,value:"xml",ready:function(xml){
			var map=$util.toMapByXML(xml);
			var treeVar=map.getItemTree('var');
			var status=treeVar.v('status');
			if(status=='succeed'){
				if(opt.succeed) opt.succeed();
			}else{
				alert(treeVar.v('error_message'));
			}
		},error:true})
	},	
'':''};


ui.fbm={_relates:{}};
extendo(ui.fbm,{px_pane:'fbm-body-',
	relates:function(v){this._relates=v},
	
	init:function(jwrap){var that=this;$(function(){that.initer(jwrap)})},
	initer:function(jwrap){
		if(!jwrap) jwrap=$('#fbm-wrap');
		if(!jwrap.length){
			$('#fbm-tab').hide();
			return
		}
		this.jwrap=jwrap;
		this.jtarget=this.jwrap.attrd('target')?$(this.jwrap.attrd('target')):this.jwrap;
		var Ddef=this.jwrap.attr('data-define')||this.jwrap.find('[el="define"]').html();
		var arDdef=Ddef.split("$$$");
		var iDef=s2o(arDdef[0],";","="),iVar=s2o(arDdef[1],";","="),iNow;
		var ifirst='';
		var htmla=[];
		htmla.push('<ul class="tab-nav">');
		for(var key in iVar){
			var tit=iVar[key];this.tabi=this.tabi||key;
			if(tit){
				iDef["default"]=iDef["default"]||key;
				htmla.push('<li><a href="#'+this.px_pane+key+'" fbm-key="'+key+'">'+tit+'</a></li>');	// data-toggle="tab"
			}
		}
		htmla.push('</ul>');
		this.jtarget.html(htmla.join(BR));
		
		var _default=$f.getValue('frm_post._multibar');
		if(!_default) _default=iDef['default'];
		if(!_default) _default=this.tabi;
		this.jtabs=this.jtarget.find('.tab-nav');
		var jtab=this.jtabs.finder('a[fbm-key="'+_default+'"]');
		if(!jtab){
			jtab=this.jtabs.finder('a[fbm-key]:first');
			_default=jtab?jtab.attrd('key'):'';
		}
		if(jtab){
			jtab.parent('li').addClass('active');
			$('#'+this.px_pane+_default).addClass('active');
		}
		this.jtabs.on('click.tab.data-api','[fbm-key]',function(e){
			e.preventDefault()
			$(this).tab('show')
		});
	},
	click:function(key){
		$f.v('frm_post._multibar',key);
		this.jtabs.find('a[fbm-key="'+key+'"]').trigger('click');
	},
	
	item:function(key){
		$f.v('frm_post._multibar',key);
		for(ik in this._relates){
			if(inp(this._relates[ik],key)>0) $j(this.px_pane+ik).show();
			else $j(this.px_pane+ik).hide();
		}
	},
	load:function(){
		$f.v('frm_post._multibar',this.k1);
		this.jwrap.find('[el="loading"]').hide();this.jbar.show();
	},
	loading:function(def_,defs_){
		this.jwrap.html(this.toString(def_,defs_));
		this.init();
	},
	toString:function(def_,defs_){
		var htmla=[];
		htmla.push('<div el="bar" class="bar" style="display:none;"><table class="multibar">');
		htmla.push('<tr class="multibar"><td colspan="2"><div class="multibar-items" el="items"></div></td></tr>');
		htmla.push('</table></div>');
		htmla.push('<div el="loading" class="loading multibar-loaddata"><span>数据加载中...</span></div>');
		htmla.push('<xmp el="tpl"><a id="fbm-bar-{key}" _key="{key}" href="javascript:;" onclick="javascript:ui.fbm.click(\'{key}\');"><span>{title}</span></a></xmp>');
		htmla.push('<xmp el="define">default=' + def_ + '$$$' + defs_ + '</xmp>');
		return htmla.join(BR)
	},
'':''});

ui.editor={
	loader:function(callback){
		if(this.isloader){
			if(this.isloader>1) callback&&callback();
			return;
		}this.isloader=1;
		var that=this;
		var urlRes=[];
		if(!window.CKEDITOR){
			//urlRes.push($c.url('script')+'ckeditor/xeditor.js');
			//urlRes.push($c.url('script')+'ckeditor/ckeditor.js');
		}
		//urlRes.push($c.url('images')+'common/upload/uploadm.js'');
		//urlRes.push($c.url('images')+'common/upload/uploadm.css');
		urlRes.push($c.url('images')+'themes/widget/ui/editor.js');
		urlRes.push($c.url('images')+'themes/widget/ui/editor.css');
		//dbg.o(urlRes);
		$.include(urlRes,function(){
			that.isloader=2;
			callback&&callback();
		});
	},
'':''};



;(function(factory) {
	if (typeof module === 'function') {
		module.exports = factory(this.jQuery || require('jquery'));
	} else {
		this.NProgress = factory(this.jQuery);
	}
})(function($) {
	var NProgress = {};
	NProgress.version = '0.1.2';
	var Settings = NProgress.settings = {
		minimum: 0.08,
		easing: 'ease',
		positionUsing: '',
		speed: 200,
		trickle: true,
		trickleRate: 0.02,
		trickleSpeed: 800,
		showSpinner: true,
		template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
	};
	NProgress.configure = function(options) {
		$.extend(Settings, options);
		return this;
	};
	NProgress.status = null;
	NProgress.set = function(n) {
		var started = NProgress.isStarted();
		n = clamp(n, Settings.minimum, 1);
		NProgress.status = (n === 1 ? null : n);
		var $progress	= NProgress.render(!started),
			$bar	= $progress.find('[role="bar"]'),
			speed	= Settings.speed,
			ease	= Settings.easing;
		$progress[0].offsetWidth;
		$progress.queue(function(next) {
			if (Settings.positionUsing === '') Settings.positionUsing = NProgress.getPositioningCSS();
			$bar.css(barPositionCSS(n, speed, ease));
			if (n === 1) {
				$progress.css({ transition: 'none', opacity: 1 });
				$progress[0].offsetWidth;
				setTimeout(function() {
					$progress.css({ transition: 'all '+speed+'ms linear', opacity: 0 });
					setTimeout(function() {
						NProgress.remove();
						next();
					}, speed);
				}, speed);
			} else {
				setTimeout(next, speed);
			}
		});
		return this;
	};
	NProgress.isStarted = function() {return typeof NProgress.status === 'number';};
	NProgress.start = function() {
		if (!NProgress.status) NProgress.set(0);
		var work = function() {
			setTimeout(function() {
				if (!NProgress.status) return;
				NProgress.trickle();
				work();
			}, Settings.trickleSpeed);
		};
		if (Settings.trickle) work();
		return this;
	};
	NProgress.done = function(force) {
		if (!force && !NProgress.status) return this;
		return NProgress.inc(0.3 + 0.5 * Math.random()).set(1);
	};
	NProgress.inc = function(amount) {
		var n = NProgress.status;
		if (!n) return NProgress.start();
		if (typeof amount !== 'number') {
			amount = (1 - n) * clamp(Math.random() * n, 0.1, 0.95);
		}
		n = clamp(n + amount, 0, 0.994);
		return NProgress.set(n);
	};
	NProgress.trickle = function() {return NProgress.inc(Math.random() * Settings.trickleRate);};
	NProgress.render = function(fromStart) {
		if (NProgress.isRendered()) return $("#nprogress");
		$('html').addClass('nprogress-busy');
		var $el = $("<div id='nprogress'>").html(Settings.template);
		var perc = fromStart ? '-100' : toBarPerc(NProgress.status || 0);
		$el.find('[role="bar"]').css({
			transition: 'all 0 linear',
			transform: 'translate3d('+perc+'%,0,0)'
		});
		if (!Settings.showSpinner) $el.find('[role="spinner"]').remove();
		$el.appendTo(document.body);
		return $el;
	};
	NProgress.remove = function() {
		$('html').removeClass('nprogress-busy');
		$('#nprogress').remove();
	};
	NProgress.isRendered = function() {return ($("#nprogress").length > 0);};
	NProgress.getPositioningCSS = function() {
		var bodyStyle = document.body.style;
		var vendorPrefix = ('WebkitTransform' in bodyStyle) ? 'Webkit' :
					 ('MozTransform' in bodyStyle) ? 'Moz' :
					 ('msTransform' in bodyStyle) ? 'ms' :
					 ('OTransform' in bodyStyle) ? 'O' : '';
		if (vendorPrefix + 'Perspective' in bodyStyle) {return 'translate3d';}
		else if (vendorPrefix + 'Transform' in bodyStyle) {return 'translate';}
		else {return 'margin';}
	};
	
	function clamp(n, min, max) {
		if (n < min) return min;
		if (n > max) return max;
		return n;
	}
	function toBarPerc(n) {return (-1 + n) * 100;}
	function barPositionCSS(n, speed, ease) {
		var barCSS;
		if (Settings.positionUsing === 'translate3d') {
			barCSS = { transform: 'translate3d('+toBarPerc(n)+'%,0,0)' };
		} else if (Settings.positionUsing === 'translate') {
			barCSS = { transform: 'translate('+toBarPerc(n)+'%,0)' };
		} else {
			barCSS = { 'margin-left': toBarPerc(n)+'%' };
		}
		barCSS.transition = 'all '+speed+'ms '+ease;
		return barCSS;
	}
	
	NProgress.initer = function() {
		NProgress._isinit=true;
		$(function(){NProgress.done()});
		return NProgress.start()
	};
	NProgress.isInit = function() {return this._isinit}

	ui.progressi=NProgress;
	return ui.progressi;
});
