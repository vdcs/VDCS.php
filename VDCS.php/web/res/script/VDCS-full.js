/*
Version:	VDCS Common & Instantiation Library v2.0
		in jquery 1.10.2
Support:	http://go.hpns.cn/vdcs/js
Uodated:	2014-01-23
*/

var VDCS={},dcs={ver:{bulid:'0.2.6.3',d:'20140100'}};
var d=document,dE=d.documentElement,dO=null,w=window;


eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('F 4B=a8="\\n";3e.4h=B(){F 2i=3m[0]||{},i=1,al=3m.K,6l=V,6m;if(1x(2i)===\'9z\'){6l=2i;2i=3m[1]||{};i=2}if(1x(2i)!==\'3S\'&&!5d(2i))2i={};if(al==i){2i=A;--i}O(;i<al;i++)if((6m=3m[i])!=12)O(F 1d in 6m){F 3X=2i[1d],2A=6m[1d];if(2i===2A)fe;if(6l&&2A&&1x(2A)===\'3S\'&&!2A.fg)2i[1d]=3e.4h(6l,3X||(2A.al!=12?[]:{}),2A);R if(2A!==2H)2i[1d]=2A}E 2i};3e.fh=B(o){E 3e.4h({},o)};3e.3q=3e.87=3e.fj=B(o,4v,14){$(o).3q(4v,14)};F fi={fd:B(){E B(){A.fc.f7(A,3m)}}};F f6={o:B(){F C,53=3m.K;O(F i=0;i<53;i++){F 14=3m[i];1A{C=14();N}1E(e){}};E C}};4h=B(o,a,b,c,d,e){E $.4h({},o,a,b,c,d,e)};5P=B(o,a,b,c,d,e){E 3e.4h(o,a,b,c,d,e)};$j=B(o,i){E 1b(o)?$(o):$((i?\'#\':\'\')+o)};$jo=B(o){o=$j(o);if(!o.K)o=12;E o};1n.d=1n.68||B(){E T 1n().5R()};5P(1n.8z,{8J:{w:f5,d:f8,h:f9,i:af,s:3n},6c:B(s){F d=T 1n();d.7n(1n.2Y(s.1i(/-/gi,"/")));E d},fb:B(1J){1J=1J||\'9O-9L-dd hh:ii:9N\';[\'9O\',\'9L\',\'dd\',\'hh\',\'ii\',\'9N\'].4T(B(v,2u){1J=1J.1i(T 4U(v,\'g\'),\'$\'+(++2u))});E A.a6().1i(/^"(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)"$/g,1J).1i(/\\{\\d+\\}/,B(2u){2u=2u.7X(/\\{(\\d+)\\}/)[1];2u=2u.2e(\'0\')==0?2u.1s(\'\')[1]:2u;2u=4e(2u)-1;E $v[\'1K.8y\'][2u]})},fk:B(){E $v[\'1K.9P\'][0]+$v[\'1K.9P\'][1].80(A.fl())},3u:B(5w,9r){E T 1n(A.5R()+9r*A.8J[5w])},4G:B(5w,d){E 4e((d.5R()-A.5R())/A.8J[5w])},fw:B(d){F n=A.4G(\'s\',d==0?T 1n():$v.8A),C=[0,\'\',-1];if(n>9R){C[0]=3;C[2]=4e(n/9R);if(C[2]<1)C[2]=1}R if(n>9A){C[0]=2;C[2]=4e(n/9A);if(C[2]<1)C[2]=1}R if(n>60){C[0]=1;C[2]=4e(n/60);if(C[2]<1)C[2]=1}R if(n>0){C[0]=0;C[2]=n;if(C[2]<1)C[2]=1}if(C[2]>0)C[1]=r($v[\'1K.fx\'][C[0]],\'$1\',C[2]);E C},a6:B(){E\'"\'+A.7k()+\'-\'+(A.7l()+1).5x(2)+\'-\'+A.7m().5x(2)+\' \'+A.7t().5x(2)+\':\'+A.7u().5x(2)+\':\'+A.7C().5x(2)+\'"\'}});5P(fA.8z,{9U:B(){E A.1i(/(^\\s*)|(\\s*$)/g,\'\')},fz:B(){E A.1i(/^\\s+/,\'\').1i(/\\s+$/,\'\')},26:B(){E A.1s(\'\')}});9u=B(s){d.aQ(s)};7q=B(s){E(s==12||s==2H)?J:V};fu=B(v){E(1x(v)==2H||1x(v)==\'2H\')?J:V};23=B(s){E(s==12||s.K<1)?J:V};ft=B(s){E(s==J||s==1||s==\'ag\'||s==\'J\'||s==\'1\')?J:V};7G=B(s){E 1x(s)==\'4z\'};4L=B(o){F C=V;if(1b(o)){1A{if(o.K>-1)C=J}1E(e){}}E C};5d=B(o){E o a7 fo||1x(o)==\'B\'};fn=B(o){E o a7 fp};1b=B(o){E(1x(o)==\'3S\'&&o)?J:V};fq=B(s){E 1x(s).2h()};fs=3z=B(s){E(4e(s)==s)};fr=7F=B(s){E(7V(s)==s)};f4=B(s){E/^[0-9]*[1-9][0-9]*$/.4r(s)?J:V};f3=eH=B(s,t,v){F 5p=(t==1)?\'ac.\':\'ac\',ar=T 2d(1);if(23(v)){ar[0]=V;ar[1]=J}R{ar[0]=0;ar[1]=s}if(23(s))E ar[0];O(F i=0;i<s.K;i++){if(5p.2e(s.80(i))==-1)E ar[0]}E ar[1]};eG=B(o,t){E 1b(o)?o:$o(o,t)};eI=eJ=B(s){E(s==J||s==1||s==\'ag\'||s==\'J\'||s==\'1\')};3d=eK=B(s,b){E 3z(s)?4e(s,b||10):0};6g=eE=B(s){E 7F(s,1)?7V(s):0};t=B(s){E 1x(s)==\'4z\'?s.9U():s};1V=B(s){E s.1i(/[^\\au-\\av]/g,\'aa\').K};r=B(s,1B,2Z){if(!s)E s;if(!23(1B)){aX(s.2e(1B)>-1){s=s.1i(1B,2Z)}};E s};ez=B(s,1B,2Z){E r(s,\'{\'+1B+\'}\',2Z)};1Z=B(s,1B,2Z){E r(s,\'{$\'+1B+\'}\',2Z)};ey=B(s,1B,2Z,ic){if(!4U.8z.eA(1B)){E s.1i(T 4U(1B,(ic?\'g\':\'gi\')),2Z)}R{E s.1i(1B,2Z)}};eB=eD=5f=B(s,n){E s.2X(0,n)};eC=eM=7O=B(s,n){E s.2X(s.K-n)};aw=B(s,c){E s==2H?-1:s.2e(c)};2a=B(s,v,p){p=p||\',\';E s==2H?-1:((p+s+p).2e(p+v+p)+1)};eN=B(s,v,p){p=p||\',\';v=v.1s(\',\');F C=0;O(F a=0;a<v.K;a++){C=(p+s+p).2e(p+v[a]+p)+1;if(C>0)N}E C};eY=B(o,2o,1z){2o=2o||\';\';1z=1z||\'=\';F C=\'\';if(1b(o)){O(F k in o){if(7G(o[k]))C+=2o+k+1z+o[k]}if(C)C=C.4Q(2o.K)}E C};eX=B(s,2o,1z){s=s||\'\';2o=2o||\';\';1z=1z||\'=\';F C={},ar,37=s.1s(2o),53=37.K;O(F i=0;i<53;i++){ar=37[i].1s(1z);if(ar.K==2&&t(ar[0])!=\'\')C[t(ar[0])]=t(ar[1])}E C};25=B(o,a,b,c,d,e){E $.4h({},o,a,b,c,d,e)};$v=1M.v={8A:T 1n(),\'1K.8y.f0\':[\'f2\',\'f1\',\'eW\',\'eV\',\'a0\',\'eQ\',\'eP\',\'eO\',\'eR\',\'eS\',\'eU\',\'eT\'],\'1K.8y.en\':[\'fC\',\'gm\',\'gl\',\'gn\',\'a0\',\'gp\',\'gr\',\'gq\',\'gk\',\'gj\',\'gd\',\'gc\'],1L:{gb:\'元\',ge:\'元\',gf:\'元\',6u:\'金币\',6V:\'分\',73:\'点\',gh:\'点\',gg:\'点\'}};$4N=$a1=1M.a1={};$3h=1M.3h={};$a={};$v.v=$4N.v=B(k,v){E 23(v)?A[k]:A[k]=v};$v.ab=B(s){A.gs=s;A.8A=T 1n().6c(s)};F 31={70:\'\',id:-1,1d:\'\'};$c=1M.gF=1M.6n={41:J,6d:\'gE\',P:{\'U\':\'/\',\'1p\':\'/\',\'1p.3b\':\'3b/\',\'1p.a3\':\'3b/a3/\',\'1p.3O\':\'3b/3O/\',\'1p.9Y\':\'3b/3O/9Y/\',\'1p.6n\':\'6n/\',\'1p.9W\':\'9W/\',\'1p.a4\':\'a4/\',\'1p.ah\':\'ah/\',\'1p.ad\':\'ad/\',\'1p.63.9G\':\'63/\',\'1p.63\':\'63/3i/\'},2p:\'\',p:\'\',m:\'\',2K:\'\',x:\'\',3Q:\'\',1w:\'\',aN:\'gJ-8\',gI:\'2z/1T\',gD:\'2z/X\',a8:\'\\n\',gC:\'a9\',gx:\'<3K 5O="gw">a9</3K>\',gv:\'[gy]\',6G:/\\{\\@([^{\\@}]*)}/gi,6C:/\\{\\$([^{\\$}]*)}/gi,3Y:B(C,1l,3w,14){14=14||(4L(1l)?B(s,1B){E 1l[1B]}:B(s,1B){1A{E 1l?1l.36(1B):\'\'}1E(ex){E 1l[1B]}});E C.1i(3w,14)},bU:B(C,1l){E A.3Y(C,1l,A.6G)},8u:B(C,1l){E A.3Y(C,1l,A.6C)},v:B(k,v){E v?$c.59(k,v):$c.5c(k)},5c:B(k){E A.P[k]},59:B(k,v){A.P[k]=v},aO:B(t){F C=A.41?1n.d():1M.55.d;E t?(\'gz=\'+C):C},gB:B(79,8U,9C,41){$v.ab(79);if(8U)1M.55.d=8U;A.9D=9C;A.8X=(A.9D==\'8X\');A.41=(41==\'l\'||41==\'41\')?J:V;A.9E()},9E:B(8W){if(8W)A.6d=8W;A.fN=A.8X?\'1T\':A.6d},fM:B(d,u,t){if(!23(d))A.P[\'1p\']=d;if(!23(u))A.P[\'U\']=u;if(!23(t))A.P[\'1p.fO\']=(t==\'fP\')?A.P[\'1p.63.9G\']:t},fR:B(6u,6V,73){5P($v.1L,{6u:6u,6V:6V,73:73})},fL:B(2p,p,m,2K){A.2p=2p;A.p=p;A.m=m;A.2K=2K},9d:B(){E{2p:A.2p,p:A.p,m:A.m,2K:A.2K,x:A.x,3Q:A.3Q,1w:A.1w}},fK:B(70,id,1d){if(1x 31==\'2H\')E;31.70=70;31.id=3d(id);if(1d)31.1d=1d;31.fF=A.U(\'3b\')+\'31/\'},fE:B(k,v){A.P[\'1p.\'+k]=v},fD:B(k){E A.P[\'1p.\'+k]},fG:B(k,v){A.P[\'U.8N.\'+k]=v},U:B(k,f,a){F C=\'\';if(k)C=A.P[\'1p.\'+k]?A.P[\'1p.\'+k]:(A.P[\'U.\'+k]?A.P[\'U.\'+k]:\'\');if(C.4Q(0,1)!=\'/\'&&C.2e(\'://\')==-1)C=A.P[\'U\']+C;if(f)C+=A.P[\'U.8N.\'+f]?A.P[\'U.8N.\'+f]:\'\';if(a)C=$U.2V(C,a);E C},3t:B(k,f,a){E A.U(k,f,a)},\'\':\'\'};$b=1M.5T={fH:8P,3L:8P.fJ.ay(),55:8P.fI,b1:B(){F C={3L:A.3L,5o:A.55.1s(\' \')[0],8w:/8w/.2y(A.3L),8R:/8R/.2y(A.3L),9B:/9J/.2y(A.3L),9M:/9M/.2y(A.3L),8x:/8x/.2y(A.3L),9I:!!w.9I,fT:(d.g4==\'g3\')};C.v=3s.aS(C.5o);C.9J=C.ev=C.9B;C.8R=5d(w.g7);C.8x=5d(w.g2);if(C.8w){F 8e=A.55.7X(/g1 ([\\d]+)\\.([\\d]+);/);C.ie=J;C.5G=C.fW=8e[1];C.fV=8e[2];C.fU=C.5G<9;C.fX=(C.5G==6);C.fY=(C.5G==7);C.g0=(C.5G==8)}E C},3o:B(){F b=$b.5T=$b.b1();O(F k in b){$b[k]=b[k]}if(!$.5T)$.5T=$b.5T},aT:B(){if(A.aj)E;A.aj=J;A.L={};A.L[\'6K\']=d.6K;A.L[\'b2\']=d.b2;A.L[\'8b\']=w.6E.2h();F 8f=A.L[\'8b\'].1s(\'://\');A.L[\'gK\']=8f[0];A.L[\'b3\']=d.b3;A.L[\'2p\']=\'\';A.L[\'aY\']=0;O(F a=0;a<5;a++){A.L[\'1p\'+(a+1)]=\'\'}F 26=8f[1].1s(\'/\');if(26.K>2){A.L[\'2p\']=26[1];A.L[\'aY\']=26.K-2;O(F a=2;a<(26.K-1);a++){A.L[\'1p\'+(a-1)]=26[a]}A.L[\'3O\']=26[26.K-1]}R{A.L[\'3O\']=26[1]}if(A.L[\'3O\'])A.L[\'1G\']=A.L[\'3O\'].2X(0,A.L[\'3O\'].cX(\'.\'))},3t:B(k){A.aT();E A.L[k||\'8b\']},U:B(k){E A.3t(k)},42:B(k){E A.3t(k)},dj:B(){if(!A.8a){A.8a=A.8k()}E A.8a},8k:B(){F C=12;if(w.5C){F ar=T 2d(\'bM.85\',\'di.85\',\'d9.85\');F 53=ar.K;O(F i=0;i<53;i++){1A{C=T 5C(ar[i])}1E(e){C=12}if(C!=12)N}}R if(w.b6){C=T b6()}E C}};$b.3o();$w=1M.dl={w:-1,h:-1,86:-1,hs:-1,3a:-1,bh:-1,3o:B(){if(A.4b)E;A.4b=J;$(B(){$w.88();$w.6W(B(){$w.3o()})})},88:B(){F 3P=$(d);A.w=3P.22();A.h=3P.2m();A.be=$(w).22();A.hi=$(w).2m();A.86=w.4A.22-18;A.hs=d.2s.bj;if(A.3a<1)A.3a=$(\'#d4\').22();A.3a=A.3a||0;A.bh=(A.w-A.3a)/2},4D:B(){A.86=w.4A.22-18;A.hs=d.2s.bj},87:B(t,14,o){3e.3q(o?o:w,t,14)},2t:B(14,o){A.87(\'2t\',14,o)},dk:B(14,o){$(o?o:w).3q(\'4u\',14);$(o?o:w).6W(14)},6W:B(f){A.be=$(w).22();A.hi=$(w).2m();$(w).6W(f)},4u:B(f){$(w).4u(f)},da:B(){F 19=-1,5D=-1;if(w.d7){19=w.d8}R if(dE&&dE.89){19=dE.89}R if(d.2s){19=d.2s.89}if(w.df){5D=w.dg}R if(dE&&dE.6B){5D=dE.6B}R if(d.2s){5D=d.2s.6B}E{x:19,y:5D}},cZ:B(s,p){E w.d3(s,p*3n)},b9:B(s){E b9(s)},5w:B(s,p){E w.e6(s,p*3n)},b8:B(s){E b8(s)}};$w.3o();$p=1M.1G={q:B(k){E $.4C.42(k)},aI:B(k){E 3d(A.q(k))},go:B(s){d.6E.4M=s},e5:B(){w.4u(0,0);d.2s.6B=\'e7\'},8c:B(){w.e8.cE()},6a:B(){E d.6E.ea()},9u:B(21,U,6O){A.4Y($U.2V(U,\'d=\'+1M.55.d),12,6O)},4Y:B(U,14,6O){$.4Y(U,14)},3y:B(21,1c,G,14){if(!21)E V;G=25(G);if(1b(21)){F 6U=d.2s;if(1b(1c))6U=1c;6U.e4(t);E J}2I(21){Q\'o\':$(\'ba\').3y(1c);N;Q\'e\':$(\'2s\').3y(1c);N;Q\'js\':$.4Y(1c,14);N;Q\'3V\':$.4Y(1c,14);N;Q\'bd\':$(\'ba\').3y(\'<50 21="2z/3V">\'+1c+\'</50>\');N}},e3:B(s,u){if($b.ie)w.dY.dX(u,s);R if(w.bb)w.bb.e0(s,u,\'\');R 2T($4N[\'2L\'][\'e2.dm\'])},2A:B(s){$.2A(1b(s)?o.2b():s)},8j:B(u,n,3a,8i,4u,8r){F bc=(4A.22)?(4A.22-3a)/2:0,aR=(4A.2m)?(4A.ec-8i)/2:0;F o=w.8j(u,n,\'22=\'+3a+\',2m=\'+8i+\',5f=\'+bc+\',9Z=\'+aR+\',eo=5Y,6E=5Y,er=5Y,1o=b5,et=5Y,es=\'+4u+\',ek=5Y\');if(8r){o.ej.aQ(8r)}E o},ee:B(s,t){F C=\'\';if(2a(\'1,2\',t)<1)t=0;C=$4N[\'2L\'][\'bo\'][t];E w.ed(r(C,\'$1\',s))},6S:B(id,G){E $j(id?id:\'#6S\').6S(G)},ef:B(id,G){E A.6S(id,G)},\'\':\'\'};$f=1M.2j={aB:\'[]\',2j:B(id,2U){F 1f=$(\'2j[1d="\'+id+\'"]\');if(1f.K<1)1f=$(\'2j#\'+id);if(2U&&1f&&1f.K<1)1f=12;E 1f},4J:B(id,2U){if(1b(id))E id;F 1f,5W=id;if(aw(id,\'.\')>0){F ar=id.1s(\'.\');5W=ar[1];F 8v=A.2j(ar[0],J);if(8v)1f=8v.2w(\':3p[1d="\'+5W+\'"]\')}R{1f=$(\':3p[1d="\'+5W+\'"]\')}if(1f&&1f.K<1)1f=$(\'#\'+5W+\':3p\');if(2U&&1f&&1f.K<1)1f=12;E 1f},ao:B(id,2U){E A.2j(id,2U)},o:B(id,2U){E A.4J(id,2U)},dW:B(id,2U){E A.4J(id,2U)},6U:B(o){if(!1b(o))A.dV=$f.o(o,J);if(o&&o.dz)o=o[0];E o},5c:B(id){F C=\'\',1f=A.4J(id,J);if(!1f)E C;F ar=1f.dy();O(F a=0;a<ar.K;a++){if(1V(ar[a])>0)C+=\',\'+ar[a]}if(1V(C)>1)C=C.2X(1);E C},59:B(id,1c,6P){F 1f=A.4J(id,J);if(!1f||1c===2H)E V;F dA=1f[0].21,1l=\',\'+1c+\',\';2I(1f[0].21){Q\'cO\':Q\'dB\':1f.4T(B(i){if(6P==\'am\'||2a(1l,$(A).2b())>0||2a(1l,\'ak\'+i)>0||2a(1l,\'an\'+i)>0)$(A).cG(J)});N;3i:if(1f[0].9h.ay()==\'bC\'){F at=1f.2w(\'dD\');at.4T(B(i){if(6P==\'am\'||2a(1l,$(A).2b())>0||2a(1l,\'ak\'+i)>0||2a(1l,\'an\'+i)>0)$(A).1v(\'dq\',J)})}R{1f.1v(\'1c\',6P==\'3y\'?(1f.2b()+1c):1c)}N}E J},v:B(o,v,m){E v?A.59(o,v,m):A.5c(o)},78:B(id){A.o(id).78()},dp:B(id){A.78(id)},3G:B(id){A.ao(id).3G()},do:B(id){A.3G(id)},dr:B(id){A.2j(id).bA(B(e){if(e.ds==13)E V})},dv:B(s){F o=s;if(!1b(o))o=$o(s);o=o||d.8p[s];if(1b(o)){if(o.aq)o.aq.47=J;if(o.ap)o.ap.47=J;if(o.aA)o.aA.47=J}},dQ:B(o,4o){4o=4o||w.4v;if((4o.6L==13&&4o.dP)||(4o.6L==83&&4o.dS)){A.dU(o);A.3G(o)}},4D:B(s){F o=s;if(!1b(o))o=$o(s);o=o||d.8p[s];if(1b(o))o.4D()},dT:B(s,n){n=n||($f.dO+$f.aB);F C=\'\',ar=s.1s(\',\');O(F i=0;i<ar.K;i++){C+=\'<3p 21="7M" 1d="\'+n+\'" 1c="\'+ar[i]+\'" />\'}E C},dI:B(s){F kc=w.4v.6L;E((kc>=48&&kc<=57)||(s==1&&!C&&kc==46))?J:V},aL:B(jo){4c.2j.aL(jo)},\'\':\'\'};$1S=1M.1S=B(){E T $1S.aK(3m[0])};$1S.dJ=B(n){E $v[\'1S.dK\'][n]};$1S.aK=B(){F 11=25({1c:\'ax\',aP:J,3Z:\'\',1w:\'\',aG:\'2z/1T;6O=\'+$c.aN,44:J},3m[0]);F L=11[\'U\'];if(!L)E;if(11[\'2Q\'])11[\'1w\']=(11[\'1w\']?11[\'1w\']+\'&\':\'\')+(4L(11[\'2Q\'])||1b(11[\'2Q\'])?$U.5z(11[\'2Q\']):11[\'2Q\']);11[\'3Z\']=11[\'3Z\'].hG();if(!11[\'3Z\'])11[\'3Z\']=11[\'1w\']?\'aD\':\'jF\';11[\'2t\']=11[\'2t\']||11[\'jE\'];11[\'2l\']=11[\'2l\']||11[\'gL\'];11[\'2F\']=11[\'2F\']||11[\'jH\'];F 19=$b.8k(),1Y=12;if(!19)E;if(11[\'aP\'])L=$U.2V(L,$c.aO(1));if($c.82||8g(\'1S\'))5e.t(\'1S\',L);19.8j(11[\'3Z\'],L,11[\'44\']);if(19.aH)19.aH(11[\'aG\']);F aF=B(){if(19.b0==4){if(19.1o==aW||19.1o==jy){if(11[\'2F\'])11[\'2F\']($1S.1c(19,11[\'1c\']))}R if(19.1o==0){}R{if(11[\'2l\']){if(5d(11[\'2l\']))11[\'2l\'](19.1o,19.5s);R 2T(\'cp: \'+L+\'\\jB: \'+19.1o+\'\\jA:\\n\'+19.5s+\'\')}}}R{if(11[\'2t\'])11[\'2t\'](19.b0)}};19.jJ=aF;if(11[\'3Z\']==\'aD\')19.jU(\'jT-jV\',\'9n/x-9c-2j-jW\');19.2Q(11[\'1w\']);if(!$b.ie&&11[\'44\']==V){if(11[\'2F\'])11[\'2F\']($1S.1c(19,11[\'1c\']))}};$1S.1c=B(19,t){F 1Y=\'\';2I(t){Q 1:Q\'2z\':1Y=19.5s;N;Q 2:Q\'X\':1Y=$b.ie?19.5j.X:19.5s;N;Q 3:Q\'ax\':1Y=19.5j;N;Q 4:Q\'2D\':1Y=$32.bt(19.5j);N;Q 5:Q\'3v\':1Y=$32.7r(19.5j);N;Q 6:Q\'99\':1Y=$32.4Z($b.ie?19.5j.X:19.5s);N;3i:1Y=19;N};E 1Y};Z.jS=B(s){F 1W=[];A.is=B(s){F C=V;O(F i=0;i<1W.K;i++){if(1W[i]==s){C=J;N}}E C};A.4x=B(){E 1W.K};A.1Q=B(n){E 1W[n-1]};A.3u=B(s){if(s)1W[1W.K]=s};A.4r=B(){O(F i=0;i<1W.K;i++){if(1W[i])1W[i]()}}};Z.jR=B(){F 7c=V,5q=T 2d(),5l=\'99\',7b=12;F X=\'\',1I=12,1a=12,9q=12;A.bP=B(){E X};A.jM=B(dt,14){5l=dt;7b=14};A.2Y=B(G){F W=A;if(!G[\'1c\'])G[\'1c\']=\'X\';if(!G[\'2F\'])G[\'2F\']=B(X){W.44(X)};$1S(G)};A.44=B(X){if(2a(5l,\'99\')>0){A.1I=$32.jL(X);A.1a=A.1I.2O(\'F\');if(2a(5l,\'3v\')>0)A.9q=A.1I.8Y(\'1Q\')}R if(2a(5l,\'3v\')>0){A.9q=$32.7r(X)}7c=J;if(7b)7b();A.4r()};A.3q=B(14){if(!7c)5q[5q.K]=14;R A.9a(14)};A.4r=B(){if(!7c)E;O(F i=0;i<5q.K;i++){A.9a(5q[i])}};A.9a=B(14){if(14)14()}};Z.jQ=B(){F 51=[],71=V,92=V;A.is=B(s){E A.71};A.4x=B(){E 51.K};A.3u=B(21,3X){51.17([0,21,3X])};A.4r=B(2f){if(A.71)2f();if(A.92)E;A.92=J;F 37=[];O(F n=0;n<51.K;n++){if(51[n][0]<1)37.17(51[n][2])}F W=A;$.4Y(37,B(){W.71=J;2f()})}};Z.bI=B(){F 2k=0;P=[];A.cQ=B(){E(2k>0)?V:J};A.3u=B(s){2k++;P[2k]=s};A.1y=B(s){A.3u(s)};A.2h=B(){F C=\'\';O(F i=1;i<=2k;i++){C+=P[i]+"\\n"};E C};A.bk=B(){F C=\'\';O(F i=1;i<=2k;i++){C+=P[i]+"\\n"};E C};A.j8=B(){if(2k>0)2T(A.bk())}};Z.j9=B(id,u,w,h){F 1W=[],9k=[],43={};A.67=B(k,v){if($b.ie){1W.17(\'<ja 1d="\'+k+\'" 1c="\'+v+\'">\')}R{1W.17(\' \'+k+\'="\'+v+\'"\')};9k.17(\' \'+k+\'="\'+v+\'"\')};A.jc=B(n,v){43[n]=v};A.b7=B(){F 72=T 2d(),k;O(k in 43){72[72.K]=k+"="+$U.6q(43[k])}E 72.2W(\'&\')};A.bg=B(v){A.67($b.ie?\'jb\':\'3X\',v)};A.j6=B(v){A.9t=v};if(u){A.bg(u)};A.67(\'j5\',\'j0\');A.a5=B(){F 5p=A.b7();if(5p.K>0){A.67(\'j1\',5p)}if($b.ie){E\'<3S id="\'+id+\'" 22="\'+w+\'" 2m="\'+h+\'" 6I="9j" j2="j4:jZ-jd-je-jp-jn" jq="62://jr.94.9b/jt/9m/jm/9l/jl.jg#5o=6,0,0,0">\'+1W.2W(\'\')+\'</3S>\'}R{E\'<6D id="\'+id+\'" 22="\'+w+\'" 2m="\'+h+\'" 6I="9j" \'+1W.2W(\'\')+\' 21="9n/x-9m-9l" 9y="62://9c.94.9b/go/9X"></6D>\'}};A.2h=B(){F C=\'\';if(!A.9t){C=A.a5()}R{C=\'<3v 22="\'+w+\'" 2m="\'+h+\'" aE="0" jh="0" ji="0"><1t><1k><3f 50="6F:a2;7N:7M;"><6D 50="6F:jk;z-ai:0;" jj="jY" id="\'+id+\'" 22="\'+w+\'" 2m="\'+h+\'" 6I="9j" \'+9k.2W(\'\')+\' 21="9n/x-9m-9l" 9y="62://9c.94.9b/go/9X"></6D><3f 50="6F:a2;\'+(($b.ie)?\'9f:kw(7K=0);\':\'\')+\'-kp-7K:0;z-ai:10;5f:0;9Z:0;k9:#ka;22:\'+w+\'2g;2m:\'+h+\'2g;"><a 4M="\'+A.9t+\'" 6i="7x" 50="k1:k0;aZ:aV;22:\'+w+\'2g;2m:\'+h+\'2g;"></a></3f></3f></1k></1t></3v>\'}E C};A.k3=B(o){if(o){$j(o).1T(A.2h())}R{9u(A.2h())}}};F 5e={e:B(e){2T(A.81(e))},81:B(e){F 1g=[];O(F k in e){1g.17(k+\'=\'+e[k])}E 1g.2W(4B)},2h:B(o,l){if(2a(\'9r,4z,9z\',1x(o))>0)E(l>0&&o.K>l)?o.2X(0,l)+\' ...\':o;R E\'[\'+1x(o)+\']\'},4J:B(o,4F,2P){E A.o(o,4F,2P)},o:B(o,4F,2P){if(3T(o)){F 1g=[];o.2M();O(F t=1;t<=o.4x();t++){1g.17(o.ik()+\'=\'+o.iv());o.6z()}2T(1g.2W(4B));E}F 34,1Y,2k=1,C=\'\';if(23(2P)||2P==-1)2P=30;1A{O(34 in o){if(2k>=2P){2T(C);C=\'\';2k=1}if(4F=="3W")C+=34+"\\n";R C+=34+"="+(A.2h(o[34],9H))+"\\n";2k++}if(C!=\'\'){2T(C);C=\'\'}}1E(e){5e.e(e)}},kb:B(o,4F,2P){F 34,1Y,2k=1,C=\'\';if(23(2P)||2P==-1)2P=20;1A{O(34 in o){if(4F=="3W")C+=34+"\\n";R C+=34+"="+(A.2h(o[34],9H))+"\\n";2k++}}1E(e){C=5e.81(e)}E C},km:B(o){2T(A.2D(o))},kn:B(o){A.o(A.3v(o))},cN:B(o){E A.26(o)},2D:B(o){if(3T(o))E A.26(o.69())},3v:B(o){if(8h(o))E A.26(o.69())},26:B(ar,5k){F 1g=T 2d();1g.17(\'<3v 5O="2y">\');if(1x(ar[0])==\'3S\'){F 6y=ar[0].K,6r=ar.K,4j,4R;if(!23(5k))1g.17("<1t><1k 4O=\\""+(6y+1)+"\\">"+5k+"</1k></1t>");1g.17("<1t><1k 4O=\\""+(6y+1)+"\\">kk="+6y+", kf="+6r+"</1k></1t>");O(F 33=0;33<ar.K;33++){4j=ar[33];1g.17("<1t>");1g.17("<1k>"+(33+1)+".</1k>");O(4R=0;4R<4j.K;4R++){1g.17("<1k>"+4j[4R]+"</1k>")}1g.17("</1t>")}}R{if(!23(5k))1g.17("<1t><1k 4O=\\"2\\">"+5k+"</1k></1t>");if(1b(ar)){F c=0;1g.17("<1t><1k 4O=\\"2\\">9K={$4x}</1k></1t>");O(F k in ar){if(1x(ar[k])=="4z"){1g.17("<1t><1k>"+k+".</1k><1k>"+ar[k]+"</1k></1t>");c++}}C=1Z(C,"4x",c)}R{1g.17("<1t><1k 4O=\\"2\\">9K="+ar.K+"</1k></1t>");O(F 33=0;33<ar.K;33++){1g.17("<1t><1k>"+(33+1)+".</1k><1k>"+ar[33]+"</1k></1t>")}}}1g.17("</3v>");E 1g.2W(4B)},t:B(t,s,id){id=id||\'2y-6Y\';o=$j(\'#\'+id);if(7q(s)){s=t;t=\'\'}R{F b4=t.1s(\':\');if(2a(\'U,1S,kh\',b4[0])>0)s=\'<a 4M="\'+s+\'" 6i="7x">\'+s+\'</a>\';s=\'<t>\'+t+\'</t>\'+s}if(!o.K){o=$(\'<3f></3f>\').1v(\'id\',id).8M(\'2s\')}o.ki(\'3o\',B(){F 3V=\'#2y-6Y in{aZ:aV;az-7O:ht;7p-2m:\'+($w.hs-aW)+\'2g;7N:7M;7N-y:hv;}\';3V+=\'#2y-6Y t{7L:#hx;hw-hq:hp;hk-7O:aM;}\';3V+=\'#2y-6Y a{7L:#aJ;}\';$p.3y(\'bd\',3V);$(\'<in></in>\').8M(o);o.3V({az:\'aM hn\',hy:\'#hz\',7L:\'#aJ\',\'2z-6I\':\'5f\',\'hK-2m\':1.5,\'aE-hH\':5,7K:0.5,6F:\'hB\',hA:40,5f:20,hC:1}).1v(\'3o\',\'J\').5Q()});o.2w(\'in\').hD(\'<p>\'+s+\'</p>\')}};8g=B(v){F 1Y=$9p.q(\'82\');if(7G(v)&&v==1Y)E J;R E V;if(1Y)E J;E V};if(!aC)F aC={hg:B(){}};$9p={q:B(k){E $.4C.42(k)},aI:B(k){E 3d(A.q(k))},42:B(k){E A.q(k)}};4C=B(k){E $.4C.42(k)};gV=B(k){E 3d($.4C.42(k))};gU=B(k){E 6g($.4C.42(k))};$cw=$3x={gX:B(1P){E 1P.1i(/[^\\gZ-\\gY]/g,\'aa\').K},1V:B(1P){F 1V=1P.K,3J=0;O(F i=0;i<1V;i++){if(1P.6Q(i)<27||1P.6Q(i)>as){3J+=2}R{3J++}}E 3J},gT:B(1P,1V){F l=1P.K,7Z=[],3J=0;O(F i=0;i<l&&3J<1V;i++){7Z[i]=1P[i];if(1P.6Q(i)<27||1P.6Q(i)>as){3J+=2}R{3J++}}E 7Z.2W(\'\')},bf:B(1P,1V,3E){F C=\'\';if(3E==12)3E=\'..\';3E=3E||\'\';F 7W=/[^\\au-\\av]/g,7S=1P.1i(7W,\'**\').K,3k=0,6J=\'\';O(F i=0;i<7S;i++){6J=1P.80(i).2h();if(6J.7X(7W)!=12)3k+=2;R 3k++;if(3k>1V)N;C+=6J}if(7S>1V)C+=3E;E C},4s:B(C,t,c,3E){if(!C)E\'\';if(t==2){C=C.1i(/<(.*)>.*.<\\/\\1>/ig,\'\');C=C.1i(/<[\\/]?([a-7i-7T-9]+)[^>^<]*>/ig,\'\');C=C.1i(/\\[[\\/]?([a-7i-7T-9]+)[^]^[]*]/ig,\'\');C=C.1i(/(\\r\\n)/ig,\'\');C=C.1i(/&7U;[\\/]?([a-7i-7T-9]+)[^&^;]*&gt;/ig,\'\')}C=r(C,"\\"", "&bl;");C=r(C,"\'", "&#39;");C=r(C,"<","&7U;");C=r(C,">","&gt;");if(c)C=A.bf(C,c,3E);E C},h0:B(s){E A.4s(s,t)},7Q:B(s){E s.1i([\'&\',\'"\',"\'",\'<\',\'>\',\'’\'],[\'&h1;\',\'&bl;\',\'&bi;\',\'&7U;\',\'&gt;\',\'&bi;\'])},hd:B(C){E C=C.1i(/<[^>]*>/g,\'\')},he:B(s,1J,k){if(!k)k=\'1J\';if(!1J)1J=\'{$\'+k+\'}\';E 1Z(1J,k,s)},7j:B(s,n){F C=\'\',s=\'\'+s+\'\',1V=s.K,77=s.2e(\'.\',0);if(77==-1){C=s+\'.\';O(i=0;i<n;i++){C=C+\'0\'}}R{if((1V-77-1)>=n){F 3k=1;O(j=0;j<n;j++){3k=3k*10}C=3s.6t(7V(s)*3k)/3k}R{C=s;O(i=0;i<(n-1V+77+1);i++){C=C+\'0\'}}}E C},h2:B(s){E A.7j((7F(s)?s:\'\'),2)},h5:B(1P){F 7E=/(\\d+)(\\d{3})/,x=1P.1s(\'.\'),5H=x[0],aU=x.K>1?\'.\'+x[1]:\'\';aX(7E.2y(5H)){5H=5H.1i(7E,\'$1\'+\',\'+\'$2\')}E 5H+aU},iD:B(n){E 3s.6t(3s.7y()*n)},iC:B(5n,7p){E 3s.aS(3s.7y()*(7p-5n+1)+5n)}};$ck=$5a={v:B(k,v,76){E 7q(v)?A.59(k,v,76):5c(k)},59:B(k,v,76){F 3D=0;2I(76){Q\'4n\':3D=1;Q\'iB\':3D=7;Q\'7v\':3D=30;Q\'7w\':3D=9T;Q\'b5\':3D=iu}if(3D>0){F 7a=T 1n();7a.7n(7a.5R()+(3D*24*60*60*3n));F bm=\'; ix=\'+7a.iy();d.5a=k+\'=\'+v+bm+\'; iI=\'+$c.P[\'1p\']}E v},5c:B(k){F C=\'\',7s=T 4U(iT(k)+"=([^;]+)");if(7s.2y(d.5a+\';\')){7s.4r(d.5a+\';\');C=9Q(4U.$1)}E C},iU:B(1d){F dc=d.5a,6X=1d+\'=\',2M=dc.2e(\'; \'+6X);if(2M==-1){2M=dc.2e(6X);if(2M!=0)E 12}R{2M+=2}F 5t=d.5a.2e(\';\',2M);if(5t==-1)5t=dc.K;E 9Q(dc.4Q(2M+6X.K,5t))}};$79=$3H=1M.3H={1K:T 1n(),4q:T 2d(),3I:B(){E 3s.6t(1n.d()/3n)},v:B(t,3P){E A.3l(3P||A.1K,t)},iQ:B(){A.4q[\'7o\']=A.4q[\'7o\']||A.3l(A.1K,\'1K\');E A.4q[\'7o\']},iK:B(r){if(r)E A.3l(T 1n(),\'3H\');A.4q[\'68\']=A.4q[\'68\']||A.3l(A.1K,\'3H\');E A.4q[\'68\']},6c:B(s){if(3z(s))E T 1n(s*3n);F d=T 1n();d.7n(1n.2Y(s.1i(/-/gi,"/")));E d},7j:B(d){F C=1n.2Y(d)/3n;if(C<1)C=0;E C},4E:B(s){if(s.2h().K<2)s=\'0\'+s;E s},3l:B(d,t,s){F C=\'\';d=1b(d)?d:A.6c(d);2I(t){Q\'1K\':C=\'Y-M-D\';N;Q\'7Y\':C=\'M-D H:I\';N;Q\'iq\':C=\'y-M-D H:I\';N;Q\'9F\':C=r(A.3l(d,\'3H\'),\' \',\'<br/>\');if(s==\'s\')C=\'<3K 5O="9F">\'+C+\'</3K>\';E C;N;Q\'3H\':C=\'Y-M-D H:I:s\';N;3i:C=t?t:\'Y-M-D H:I:s\';N}C=r(C,\'Y\',d.7k());C=r(C,\'y\',d.7k().2h().2X(2));C=r(C,\'M\',A.4E(d.7l()+1));C=r(C,\'m\',d.7l()+1);C=r(C,\'D\',A.4E(d.7m()));C=r(C,\'d\',d.7m());C=r(C,\'H\',A.4E(d.7t()));C=r(C,\'h\',d.7t());C=r(C,\'I\',A.4E(d.7u()));C=r(C,\'i\',d.7u());C=r(C,\'S\',A.4E(d.7C()));C=r(C,\'s\',d.7C());E C},i2:B(v,n,p,t){F dn,7D=1,7A=7D*3n,4W=7A*60,4k=4W*60,4n=4k*24,7v=4k*24*30,7w=4n*9T;2I(p){Q\'hW\':dn=7D*n;N;Q\'s\':dn=7A*n;N;Q\'2K\':dn=4W*n;N;Q\'h\':dn=4k*n;N;Q\'m\':dn=7v*n;N;Q\'y\':dn=7w*n;N;Q\'d\':3i:dn=4n*n;N}F 3P=T 1n(T 1n(T 1n(v.1i(\'-\',\',\'))).hS()+dn);E t?A.3l(3P,t):3P},hT:B(t,d1,d2){F 9V=1n.2Y(d1.1i(/-/gi,"/")),ae=1n.2Y(d2.1i(/-/gi,"/"));F C=9V-ae;2I(t){Q\'h\':C=C/ij;N;Q\'m\':C=C/af;N;Q\'s\':C=C/3n;N}E C},io:B(n,1L){if(!1L)1L=[];if(!1L[\'4n\'])1L[\'4n\']=$v[\'1K.1L\'][3];if(!1L[\'4k\'])1L[\'4k\']=$v[\'1K.1L\'][2];if(!1L[\'4W\'])1L[\'4W\']=$v[\'1K.1L\'][1];F C=\'\',d=h=m=0,ip=n;if(n>60){m=n%60;n=(n-m)/60;C=m+1L[\'4W\']}if(n>24){h=n%24;n=(n-h)/24;C=h+1L[\'4k\']+C}if(n>0){C=n+1L[\'4n\']+C}E C}};$U=1M.U={is:B(s){E A.9S(s)},9S:B(s){E(s.2e(\'://\')!=-1||s.4Q(0,1)==\'/\')?J:V},i7:B(s){E i8.4r(T 4U("^(62(s?)|ia)://","i"))},5z:B(ar){F C=\'\';O(k in ar)C+=\'&\'+k+\'=\'+A.6q(ar[k]);if(C.K>0)C=C.4Q(1);E C},2V:B(C,54){C=C||\'\';if(4L(54))54=A.5z(54);if(54){if(C.2e(\'?\')==-1)C+=\'?\';R if(C.4Q(C.2e(\'?\')+1).K>0)C+=\'&\';C+=54}E C},jG:B(s,v,k){k=k||\'4R\';v=v||3s.7y();E A.2V(s,k+\'=\'+v)},i9:B(L,bn,3j){3j=25({4H:\'\'},3j);F C=3j.4H;if(L!=\'\'&&L!=\'62://\'){C=\'<a 4M="\'+L+\'"\';if(3j.6i)C+=\' 6i="7x"\';if(3j.5F)C+=\' 5F="\'+3j.5F+\'"\';if(3j.cv)C+=\' \'+3j.cv;C+=\'>\'+bn+\'</a>\'}E C},en:B(s){E A.6q(s)},6q:B(s){E i5(s)},i6:B(s){E ib(s)},de:B(s){E A.cg(s)},ih:B(s){E im(s)},cg:B(s){E il(s)},\'\':\'\'};$4N[\'35\']={};$35={i4:0,2t:B(U,14,2f){F 35=T i3();35.3X=U;if(14||2f){F 7z=B(){if(14)14(35);if(2f)2f.hU(35)};if(35.hR){7z();E}35.hP=B(){7z()}}},\'\':\'\'};$4N[\'5K\']={};$hQ=$5K={go:B(4H,1H,1G,4G){if(!4H)4H=\'hV\';if(!1H)1H=\'i1\';if(!1G)1G=\'{$1G}\';if(!4G)4G=0;F L=$(\'3p[1d="\'+4H+\'"]\').2b(),6s=3d($(\'3p[1d="\'+1H+\'"]\').2b())+4G;if(6s<1)6s=0;L=r(L,1G,6s);$p.go(L)},i0:B(U,7B,5y,1J){if(!3z(5y))5y=10;F C=\'\',L=\'\',2L=3s.6t(7B/5y);if((2L*5y)<7B)2L++;if(1J&&2L>1){O(F i=2;i<4;i++){if(i>2L)N;L=1Z(U,\'1G\',i);C+=\'<a 4M="\'+L+\'" 5F="cM \'+i+\'">\'+i+\'</a> \'}if(2L>3){if(2L>4)C+=\' ..\';i=2L;L=1Z(U,\'1G\',i);C+=\'<a 4M="\'+L+\'" 5F="cM \'+i+\'">\'+i+\'</a> \'}}R{C=1Z(1J,\'U\',1Z(U,"1G",2L))}E C}};hZ=B(){E T Z.2S()};hX=B(){E T Z.3R()};hY=B(){E T Z.5M()};ir=B(){E T Z.4m()};3T=B(o){F C=V;if(1b(o)){1A{if(o.3r()=="Z.2S")C=J}1E(e){}}E C};8h=B(o){F C=V;if(1b(o)){1A{if(o.3r()=="Z.3R")C=J}1E(e){}}E C};iO=B(o){F C=V;if(1b(o)){1A{if(o.3r()=="Z.5M")C=J}1E(e){}}E C};iP=B(o){F C=V;if(1b(o)){1A{if(o.3r()=="Z.4m")C=J}1E(e){}}E C};5P(Z,{6v:\'15\',iN:\'4w\',iM:\'iL\',iR:\'iW\',iV:\'.\',iS:B(o){F C=\'\';if(w.5C)C=o.2z;R{1A{C=o.9i[0].9e}1E(ex){C=\'\'}}E C}});$32=1M.32={iJ:B(s,2o,1z){if(!2o)2o=\';\';if(!1z)1z=\'=\';F C=T Z.2S();if(!s)E C;F 37=s.1s(2o),ar;O(F a=0;a<37.K;a++){ar=37[a].1s(1z);if(ar[0].K>0)C.1y(ar[0],ar[1])}E C},bt:B(X){F 1U=T Z.2S();if(X){F 15=T Z.4m();if(1b(X))15.64(X);R 15.61(X);15.6R();F 4d=15.4y(\'16\').1s(\',\');O(F a=0;a<4d.K;a++){1U.7d(15.6p(4d[a]),4d[a]+\'.\')}}E 1U},7r:B(X){F 4g=T Z.3R();if(X){F 15=T Z.4m();if(1b(X))15.64(X);R 15.61(X);15.6R();F 2c=15.95();if(2c!=\'\'){4g.6h(2c);15.97();15.5h();O(F a=0;a<15.6b();a++){4g.1y(15.2O());15.5i()}}}E 4g},4Z:B(X){F 5J=T Z.5M();if(X){F 15=T Z.4m();if(1b(X))15.64(X);R 15.61(X);15.6R();5J.1y(\'F\',15.6p(\'F\'));F 3c,a,i,2c=15.95();if(2c!=\'\'){3c=T Z.3R();3c.6h(2c);15.97();15.5h();O(a=0;a<15.6b();a++){3c.1y(15.2O());15.5i()}5J.1y(15.bT(),3c)}F 4d=15.4y(\'7J\').1s(\',\');O(a in 4d){F 5E=4d[a];2c=15.4y(\'1H.\'+5E);if(5E&&2c){3c=T Z.3R();3c.6h(2c);15.96(5E);15.5h();O(i=0;i<15.6b();i++){3c.1y(15.2O());15.5i()}5J.1y(5E,3c)}}}E 5J},iz:B(1I){E 1b(1I)?1I:A.4Z(1I)}};$8K=1M.bJ={iw:"$$$",it:"~~~$$$~~~",iA:"(.+?)",iG:"([\\w\\-\\iH\\.\\:]*)",bN:"([^>\\"]*)",iF:A.bN+"(!([\\w-\\.][^!]+?))?",iE:"([^{\\$\\"]*)",hO:"([\\s\\w-\\.\\:=;\\\'\\(\\)<>\\[\\]{\\$}]*)",hN:"(,\\"(.|[^>\\"]*)\\")?",h6:"([\\s\\S.]*?)",h7:"((.|\\n)*?)",h4:"(!([\\w-\\.\\:]+?))?",h3:"(.+?)",6G:/\\{\\@([^{\\@}]*)}/gi,6C:/\\{\\$([^{\\$}]*)}/gi,h8:/\\{\\${\\$2g}([^{\\$}]*)}/gi,h9:"<F:([^<>]*)>",hc:"<F:([^<>!]*)(!([\\w-\\.][^!]+?))?>",ha:"<bV:([^<>]*)>",hb:"<bV:([^<>!]*)(!([\\w-\\.][^!]+?))?>",gQ:"<3g:([^<>]*)>",gR:"<3g:([^<>!]*)(!([\\w-\\.][^!]+?))?>",gP:/\\[1Q:([\\w-\\.]*)\\]/gi,ca:/\\[1Q:([\\w-\\.]*)(!([\\w-\\.][^!]+?))?(!([\\w-\\.][^!]+?))?\\]/gi,3Y:B(C,1l,3w,14){E $c.3Y(C,1l,3w,14)},bU:B(C,1l){E A.3Y(C,1l,A.6G)},8u:B(C,1l){E A.3Y(C,1l,A.6C)},c9:B(C,1l,3w,14){if(!C)E\'\';E C.1i(3w,B(s,1B,2Z,c1,gO,c8){E $8K.c6(1l.36(1B),c1,c8,14)})},4X:B(C,1u,3w){E A.c9(C,1u,3w||A.ca)},c6:B(C,5U,1z,14){if(5U){2I(5U){Q\'1T\':C=$3x.4s(C,1,1z);N;Q\'gM\':C=$3x.4s(C,2,1z);N;Q\'1K\':C=$3H.3l(C,\'1K\');N;Q\'7Y\':C=$3H.3l(C,\'7Y\');N;Q\'gN\':C=$3x.4s(C,0,1z);N;Q\'2z\':C=$3x.4s(C,1,1z);N;Q\'X\':C=$3x.7Q(C);N;3i:if(14)C=14(C,5U,1z);R{F 7R=\'gS\'+5U;if($3x[7R])C=$3x[7R](C,1z)}N}}E C},7Q:B(16,1H,6N){if(!16)16=\'1Q\';F C=T bv();C.17(A.7I());C.17(\'<4w>\');C.17(\'	<16>\'+16+\'</16>\');C.17(\'	<1H>\'+1H+\'</1H>\');C.17(\'</4w>\');C.17(6N);C.17(A.7H());E C.2W(4B)},gW:B(16,1H,6N){if(!16)16=\'1Q\';F C=T bv();C.17(A.7I());C.17(\'<4w>\');C.17(\'	<7J>\'+16+\'</7J>\');C.17(\'	<1H.\'+16+\'>\'+1H+\'</1H.\'+16+\'>\');C.17(\'</4w>\');C.17(6N);C.17(A.7H());E C.2W(4B)},7I:B(){E\'<\'+\'?X 5o="1.0"?\'+\'><15 5o="1.0" hf="3g">\'},7H:B(){E\'</15>\'}};Z.bJ={};Z.2S=B(){A.1F=-1,A.1h=0,A.P=T 2d(),A.38=\'Z.2S\';A.7g=A.is=B(){E 3z(A.1F)};A.3r=B(){E A.38};A.65=A.4x=B(){E A.1F+1};A.hE=A.1V=B(){E A.P.K};A.cy=A.i=B(){E A.1h};A.69=B(){E A.P};A.6Z=A.2M=B(){A.1h=0};A.hF=B(){A.1h=(A.1F>-1?A.1F:0)};A.7f=A.6z=B(n){if(!3z(n))n=1;A.1h+=n;if(A.1h>A.1F)A.1h=A.1F;if(A.1h<0)A.1h=0};A.7e=A.ik=B(){E(A.1F>-1)?A.P[A.1h][0]:\'\'};A.56=A.iv=B(){E(A.1F>-1)?A.P[A.1h][1]:\'\'};A.1y=A.3u=B(k,v){O(F i=0;i<=A.1F;i++){if(A.P[i][0]==k){A.P[i][1]=v;E}}A.1F++;A.P[A.1F]=T 2d(k,v)};A.7h=B(k,v){O(F i=0;i<=A.1F;i++){if(A.P[i][0]==k){A.P[i][1]=v;E}}};A.cj=B(k){O(F i=0;i<=A.P.K;i++){if(A.P[i][0]==k){A.P=A.P.cV(0,i).iY(A.P.cV(i+1,A.P.K));A.1F=A.P.K-1;A.6Z();N}}};A.cd=B(k){F C=V;O(F i=0;i<=A.1F;i++){if(A.P[i][0]==k){C=J;N}}E C};A.36=A.v=B(k){F C=\'\';O(F i=0;i<=A.1F;i++){if(A.P[i][0]==k){C=A.P[i][1];N}}E C};A.hM=A.hL=B(k){E 3d(A.36(k))};A.hI=A.hJ=B(k){E 6g(A.36(k))};A.bE=B(){E A.cS(0)};A.cs=B(){E A.cF(0)};A.cS=B(t){if(23(t))t=1;F C=\'\';O(F i=0;i<=A.1F;i++){C+=\',\'+A.P[i][1]}if(C.K>0)C=C.2X(1);E C};A.cF=B(t){if(23(t))t=1;F 1g=T 2d();O(F i=0;i<=A.1F;i++){1g[i]=A.P[i][t]}E 1g};A.7d=B(1R,2g){if(3T(1R)){if(!2g)2g=\'\';1R.6Z();O(F i=0;i<1R.65();i++){A.1y(2g+1R.7e(),1R.56());1R.7f()}}};A.bX=B(2c){F 45=2c?2c.1s(\',\'):[];O(F a in 45){F 1H=45[a];if(1H)A.7P(ho.2Y(A.v(1H)),1H+\'.\')}};A.7P=B(5m,2g){O(F 16 in 5m){if(1x(5m[16])==\'3S\')A.7P(5m[16],2g+16+\'.\');R A.3u(2g+16,5m[16])}}};Z.3R=B(){A.1m=-1,A.2E=-1,A.1h=1,A.P=T 2d(),A.38="Z.3R";A.7g=A.is=B(){E A.1F>-1?J:V};A.3r=B(){E A.38};A.hm=A.8L=B(){E A.1m};A.hl=A.hj=B(){E A.2E+1};A.cy=A.i=B(){E A.1h};A.69=B(){E A.P};A.5h=A.2M=A.hu=B(){A.1h=1};A.hr=A.5t=A.iX=B(){A.1h=(A.1m>0?A.1m:1)};A.5i=A.6z=A.j3=B(2q){if(!3z(2q))2q=1;A.1h+=2q;if(A.1h>A.1m)A.1h=A.1m;if(A.1h<1)A.1h=1};A.1y=A.3u=B(1R){if(3T(1R)){if(A.1m<0&&1R.65()>0){A.1m=0;A.2E=1R.65()-1;A.P[A.1m]=1R.cs()}if(A.1m<0)E;A.1m++;A.P[A.1m]=T 2d(A.2E+1);O(F c=0;c<=A.2E;c++){A.P[A.1m][c]=1R.36(A.P[0][c])}}};A.7h=B(1R){if(3T(1R)&&A.1m>0){O(F c=0;c<=A.2E;c++){A.P[A.1h][c]=1R.36(A.P[0][c])}}};A.kg=B(k,v){if(A.1m>0){O(F c=0;c<=A.2E;c++){if(A.P[0][c]==k){A.P[A.1h][c]=v;N}}}};A.cj=B(s){if(A.1m>0){F 6r=3z(s)?s:A.1h,4j=A.P;A.1m=-1;A.P=T 2d();O(F a=0;a<4j.K;a++){if(a!=6r){A.1m++;A.P[A.1m]=4j[a]}}A.1h=1}};A.2O=B(){F 1U=T Z.2S();if(A.1m>0){O(F c=0;c<=A.2E;c++)1U.1y(A.P[0][c],A.P[A.1h][c])}E 1U};A.56=A.iv=B(k){F C=\'\';if(A.1m>0){O(F c=0;c<=A.2E;c++){if(A.P[0][c]==k){C=A.P[A.1h][c];N}}}E C};A.ke=A.kj=B(k){E 3d(A.56(k))};A.ko=A.kl=B(k){E 6g(A.56(k))};A.bE=B(){F C=\'\';if(A.1m>0){O(F c=0;c<=A.2E;c++)C+=\',\'+A.P[0][c];if(C.K>0)C=C.2X(1)}E C};A.6h=B(2c){F 45=2c.1s(\',\');if(45.K>0){A.1m=0;A.2E=45.K-1;A.P=T 2d();A.P[A.1m]=45}};A.7d=B(2D){if(1b(2D)&&2D.3r()==A.38){2D.6Z();O(F i=0;i<2D.65();i++){A.1y(2D.7e(),2D.56());2D.7f()}}}};Z.5M=B(){A.29={},A.38=\'Z.5M\';A.7g=A.is=B(){E A.29.K>0?J:V};A.3r=B(){E A.38};A.cd=B(k){E 1x(A.29[k])!=\'2H\'};A.1y=A.3u=B(k,o){A.29[k]=o};A.7h=B(k,o){A.29[k]=o};A.36=B(k,o){E A.29[k]};A.8F=B(k,o){E(1x(A.29[k])==\'4z\')?A.29[k]:\'\'};A.kd=B(k,o){F 1g=T 2d();if(4L(A.29[k]))1g=A.29[k];E 1g};A.2O=B(k,o){F 1U=T Z.2S();if(3T(A.29[k]))1U=A.29[k];E 1U};A.8Y=B(k,o){F 4g=T Z.3R();if(8h(A.29[k]))4g=A.29[k];E 4g}};Z.4m=B(){A.1h=0,A.1D=12,A.1N=V,A.93=12,A.5g=12,A.3F=0,A.3B=0,A.bS=[],A.6H=\'\',A.4K=\'\',A.38="Z.4m";A.5I=B(){F o=12;if(w.5C){F 9x=[\'bM.k4\'];O(F i=0;o<9x.K;i++){1A{o=T 5C(9x[i])}1E(e){o=12}if(o!=12)N}}R if(d.9w&&d.9w.bq)o=d.9w.bq(\'\',\'\',12);E o};A.kq=B(2q){E A.1N};A.3r=B(){E A.38};A.k2=B(s){A.1D=A.5I();if(A.1D!=12){A.1D.44=V;A.1D.bQ=V;A.1N=J}};A.64=B(o){if(1b(o)){1A{A.1D=o.k5;A.1N=J}1E(e){A.1N=V}}};A.k6=B(2q){A.1D=A.5I();1A{A.1D.2t(2q);A.1N=J}1E(e){A.1N=V}};A.k8=B(2q){A.1D=A.5I();1A{A.1D.2t(2q);A.1N=J}1E(e){A.1N=V}};A.61=B(2q){if(!$b.ie){A.64(T k7().ku(2q,\'2z/X\'))}R{A.1D=A.5I();1A{A.1D.44=V;A.1D.bQ=V;A.1D.61(2q);A.1N=J}1E(e){A.1N=V}}};A.bP=B(){E(A.1N==J)?A.1D.X:\'\'};A.6R=B(){if(!A.1N||!A.1D)E;1A{A.93=A.6x(\'4w\');A.6H=A.4y(\'16\');A.4K=A.4y(\'1H\')}1E(e){}};A.96=B(s){if(!A.1N)E;if(s.K>0){A.5g=A.6x(s);A.3F=A.5g.K;if(A.4K.K>0)A.bS=A.4K.1s(\',\')}};A.97=B(){A.96(A.6H)};A.4y=B(k){E A.9o(A.93,0,k)};A.bT=B(){E A.6H};A.95=B(){E A.4K};A.kv=B(){E A.4K};A.6b=B(){E A.3F};A.kr=B(){E A.3F};A.ks=B(){E A.3B};A.5h=B(){E A.3B=0};A.5i=B(){A.3B++;if(A.3B>(A.3F-2))A.3B=A.3F-1};A.36=B(k){F C=\'\';if(A.1N&&A.3F>0){C=A.9o(A.5g,A.3B,k)}E C};A.2O=B(){F 1U=T Z.2S();if(A.1N&&A.3F>0){1U=A.6p(A.5g,A.3B)}E 1U};A.6x=B(s){F C=12;if(A.1N==J){if($b.ie){C=A.1D.6j(Z.6v+\'/\'+s)}R{C=A.1D.kt.6j(Z.6v)[0].6j(s)}}E C};A.9o=B(16,4i,3W){F C=\'\';1A{if(16==12)16=A.1D;C=16.1Q(4i).6j(3W).1Q(0).9g.9e}1E(e){}E C};A.6p=B(16,4i){F 1U=T Z.2S();1A{if(!4i)4i=0;if(!1b(16))16=A.6x(16);if(16&&16.K){F 4p;if($b.ie){4p=16.1Q(4i).9i}R{F n=0;O(F i=0;i<16.K;i++){if(16[i].jf.9h==Z.6v){if(4i==n){4p=16.1Q(i).9i;N}n++}}}F 6e,1X;O(F c=0;c<4p.K;c++){6e=4p.1Q(c).9h;if(!23(6e)){1X=\'\';if(1b(4p.1Q(c).9g))1X=4p.1Q(c).9g.9e;1U.1y(6e,1X)}}}}1E(e){}E 1U}};Z.5Z=B(){A.9f=\'9d\';A.U=\'/p.\'+$c.6d+\'/{$2p}/{$p}/{$m}/{$2K}.{$x}?3Q={$3Q}\';A.43={2p:\'\',p:\'\',m:\'\',2K:\'\',x:\'x\',3Q:\'\'};A.iZ=B(k,v){A.43[k]=v};A.bK=B(U,f){if(A.9f==\'9d\'||f){U=r(U,\'/.\',\'.\');U=r(U,\'/.\',\'.\');U=r(U,\'/.\',\'.\')}E U};A.3t=B(G){G=25(A.43,G);F L=A.U;L=1Z(L,\'2p\',G.2p);L=1Z(L,\'p\',G.p);L=1Z(L,\'m\',G.m);L=1Z(L,\'2K\',G.2K);L=1Z(L,\'x\',G.x);L=1Z(L,\'3Q\',G.3Q);L=A.bK(L);F 1w=G.1w;if(1w)L=$U.2V(L,(4L(1w)||1b(1w)?$U.5z(1w):1w));E L}};$2j={5L:B(W){if(!W)W=A;F 3M=J,6A=V,1O={};W.4l=T Z.bI(),W.2x=12;if(1x 91!=\'2H\'){91.j7();6A=J}W.6T.2w(\'3p,bC,ju\').4T(B(i){F 2J=$(A),28=2J.1v(\'1d\');F 1d=(28&&28.2X(-2)==\'[]\')?28.2X(0,28.K-2):28;if(1d&&1x(1O[1d])==\'2H\'){F 1X=2J.jv();1O[1d]=1X;if(6A&&!91.jP(2J))3M=V;if(!6A){F cT=3d(2J.1v(\'cn\')?2J.1v(\'cn\'):(2J.1v(\'5n\')?2J.1v(\'5n\'):2J.1v(\'jO\')));if(cT>0&&!2J.2b().K)3M=V}if(!3M&&!W.2x)W.2x=2J}});W.6T.2w(\'3p[21=cO]:cG\').4T(B(){F 1d=$(A).1v(\'1d\');F 1c=$(A).2b();if(1d)1O[1d]=1c});if(W.G&&W.G.cI){F 5r=\'\';if(W.G.cH)5r=$79.3I();1O[\'jN\']=5r;F 98=W.G.cJ.1s(\',\'),4t;O(F a in 98){4t=98[a];if(1O[4t]){1O[4t]=$.cR(1O[4t]);if(5r)1O[4t]=$.cR(1O[4t]+\',\'+5r)}}}W.1O=1O;if(W.9s)W.9s();if($9p.q(\'82\')==\'3g\')5e.o(W.1O);if(3M&&W.4l)3M=W.4l.cQ();if(!3M&&W.1C&&W.4l)W.1C(\'2l\',W.4l.2h(),J);E 3M},9s:B(){},9v:B(){E 31.9v(J)},2Q:B(){if(!A.9v())E;F W=A;if(!A.5L()){E}if(A.8Z)E;A.8Z=J;F L=A.3t(\'2Q\'),5u=A.1O;if(!L){3h.1C(\'5v\',\'jX jK cp!\',J);E}$1S({U:L,2Q:5u,1c:\'X\',2F:B(o){W.cl(o)},2l:J})},cl:B(X){A.1I=$32.4Z(X);A.1a=A.1I.2O(\'F\');F 1r=A.1a.v(\'1o\');if(1r==\'2B\'){F 1j=A.8l(A.1a,\'发布成功！\');3h.1C(\'2B\',1j,J);if(A.4D)A.4D();if(A.6a)A.6a();A.cf()}R{A.8o(A.1a)}A.8Z=V},cf:B(){},8m:B(G){G=25({1C:\'1C\',5S:J},G);E G},cA:B(2b,G){G=A.8m(G);F C=V;F 1r=1b(2b)?2b.v(\'1o\'):2b;if(1r==\'2B\')C=J;if(G.5S&&1r==\'5S\')C=J;E C},jz:B(1a,G){if(A.cA(1a,G))E J;A.8o(1a,G)},8l:B(1a,8n,4l){F 1j=1a.v(\'2N\')||1a.v(\'2N.4z\')||\'\';if(!1j&&4l)1j=1a.v(\'jw\')||1a.v(\'cz\')||\'\';if(!1j&&8n)1j=8n;E 1j},8o:B(1a,G){G=A.8m(G);F 6M={};F 1r=G.1o?G.1o:1a.v(\'1o\');F 1j=A.8l(1a,\'\',J);if(!1j)1j=G[\'bY\'+1r];if(!1j){2I(1r){Q\'3o\':1j=\'数据初始化！\';N;Q\'4V\':1j=\'无效的数据解析！\';N;Q\'5S\':1j=\'数据已处理！\';N;Q\'1w\':1j=\'缺少必要的参数！\';N;Q\'3g\':1j=\'不正确的数据提交！\';N;Q\'jx\':1j=\'不正确的数据提交！\';N;Q\'jC\':1j=\'记录不存在！\';N;Q\'jD\':1j=\'权限不足！\';N;Q\'2B\':1j=\'提交成功！\';N;Q\'jI\':3i:1j=\'数据处理失败！\';N}}6M.1o=1r;6M.2N=1j;if(!1j)2T(X);R{F cx=G[\'g5\'+1r]||\'5v\';if(G.1C){2I(G.1C){Q\'8d\':4c.8d.5Q(1j);N;Q\'1C\':3i:3h.1C(cx,1j,J);N}}if(G.2f)G.2f(1r,1j)}E 6M},\'\':\'\'};Z.8p=B(G,1q){A.G=25({8q:\'dL\',bp:\'服务\',dM:\'未知的服务请求！\',dH:\'请填写必要的信息！\',dN:\'未知错误\',dR:\'处理中..\',dG:\'处理成功！\',dF:\'服务跳转中..\',5A:J,bz:\'提交中..\',by:\'提交成功！\',cb:du,bx:2,c0:J,cu:J,cI:V,cH:V,cJ:\'\',cr:\'cB\',8t:[],bL:\'\',5X:\'\',bB:J,8c:J},G);A.1q=25({3G:\'[el=3G]\',1C:\'.1C\',dw:\'90\',dx:\'3K\',\'\':\'\'},1q);A.5V=B(G){if(G)A.G=25(A.G,G)};A.bD=B(1q){if(1q)A.1q=25(A.1q,1q)};A.dC=A.c3=B(G,1q){if(A.cc)E;A.cc=J;A.5V(G);A.bD(1q);if(A.G.2s||A.2r){A.2r=A.2r?$jo(A.2r):$jo(A.G.2s);if(A.2r)A.2G=A.2r.2w(\'2j\')}R if(A.G.8q||A.2G){A.2G=A.2G?$jo(A.2G):$f.2j(A.G.8q);if(A.2G)A.2r=A.2G.eh()}if(!A.2r&&!A.2G)E;A.5B=A.1q.5B?A.1q.5B:A.2r.8I(A.1q.1C);A.2C=A.1q.2C?A.1q.2C:A.2r.8I(A.1q.3G);A.4b=J};A.ei=B(k,v){A.G.8t[k]=v},A.5Z=B(1w){F C=A.G.5X?A.G.5X:A.G.bL;C=$c.8u(C,A.G.8t);if(1w)C=$U.2V(C,1w);E C},A.5L=B(){if(!A.6T)A.6T=A.2r;E $2j.5L(A)};A.eg=B(){if(!A.4b)E;A.2r.6k()};A.c5=B(G){F W=A;if(A.2G){A.2G.3G(B(){E V});if(A.G.bB){A.2G.2w(\'3p\').58(\'bA\',B(4v){if(4v.6L==\'13\')W.4V()})}}if(!A.2C)E;A.2C.bo(B(){if($(A).1v(\'47\'))E;W.4V();E V})};A.52=B(1o,6K){if(!A.2C)E;A.5A=1o;F 1X=6K;if(!A.G.8s)A.G.8s=A.2C.2z();2I(1o){Q\'co\':Q\'eq\':A.2C.1v(\'47\',J);if(!1X)1X=A.G.bz;N;Q\'2B\':A.2C.1v(\'47\',J);if(!1X)1X=A.G.by;N;Q\'58\':3i:A.2C.1v(\'47\',V);if(!1X)1X=A.G.8s;N}if(A.G.5A&&1X)A.2C.2w(\'3K\').2z(1X)};A.cP=B(){E A.5A&&A.5A!=\'58\'};A.1C=B(1o,2N,2f,3I){3I=3I?3I:A.G.bx;if(1o==\'6k\')3h.90(A.5B,\'6k\');R 3h.90(A.5B,2N,{1o:1o,2f:2f,ep:\'.bO\',eb:\'bO\',dZ:A.G.cb,3I:3I,e9:A.G.c0})};A.49=B(3W){E A.G[\'bY\'+3W]||\'[\'+3W+\']\'};A.75=B(U){if(U)A.G.5N=U;E A.G.74||A.G.5N||$c.U(\'eu\')};A.88=B(G,1q){A.c3(G,1q);if(!A.4b)E;A.c5()};A.cU=B(){if(!A.5L()){F 84=A.49(\'db\');if(A.2x){F 28=A.2x.1v(\'bp\');if(!28)28=A.2x.1v(\'d6\');if(!28)28=A.2x.1v(\'dh\');if(!28)28=A.2x.1v(\'1d\');84=\'请输入 \'+28+\'！\';A.2x.d5(\'2l\');A.2x.cY(B(){F jo=$(A);if(jo.2b())jo.cW(\'2l\')});A.2x.78()}A.1C(\'2l\',84);if(A.G.cK)A.G.cK(A.2x);E V}E J},A.4V=B(){if(A.cP())E;if(!A.cU())E;A.1C(\'2t\',A.49(\'4V\'));A.52(\'co\');F W=A;F L=A.5Z(),5u=12;if(!L){A.1C(\'5v\',A.49(\'5Z\'));A.52(\'58\');E}if(A.G.cr==\'cB\')5u=A.1O;R L=$U.2V(L,$U.5z(A.1O));$1S({U:L,2Q:5u,1c:\'X\',2F:B(o){W.cD(o)},2l:J});E V};A.cD=B(X){if(A.d0||8g(\'1I\')){F 1e=$(\'#e1\');if(!1e||!1e.K)2T(X);R 1e.1T($cw.4s(X));A.52(\'58\');E}F W=A;A.1I=$32.4Z(X);A.1a=A.1I.2O(\'F\');F 1r=A.1a.v(\'1o\');if(2a(\'2B,5S\',1r)>0){if(A.1a.v(\'74\'))A.75(A.1a.v(\'74\'));if(A.1a.v(\'5N\'))A.75(A.1a.v(\'5N\'));A.52(\'2B\');if(A.G.cu)W.ci();if(A.G.2f)A.G.2f(1r,A.1a)}R{A.1C(\'2l\',A.1a.v(\'2N\')||A.1a.v(\'cz\')||A.49(\'fZ\')+\'(\'+1r+\')\',J);A.52(\'58\');if(A.G.cC)A.G.cC(1r,A.1a)}};A.ci=B(G){F W=A;G=25({2N:A.49(\'2B\')},G);F 8Q=B(){if(!W.G.8c)E;4c.8d.5Q(W.49(\'cE\'));$p.go(W.75())};if(A.G.74||A.G.5N){4c.g8.5Q({1o:\'2B\',2N:G.2N,g6:8Q})}A.1C(\'2B\',G.2N,8Q,1)}};Z.cN=B(bF){A.5V=B(G,5b){if(!5b)E G;if(5b.4f)5b.4f=25(G.4f,5b.4f);G=25(G,5b);E G};A.G=A.5V({c4:\'1Q\',4f:{x:\'x\'}},bF);A.fS=B(k,v){A.G.4f[k]=v};A.bH=B(G){G=25(A.G.4f,G);E $U.2V(4c.5Z.3t(G),\'1G=\'+A.1G)};A.3t=B(){E A.G.5X?$U.2V(A.G.5X,\'1G=\'+A.1G):A.bH()};A.3o=B(G){A.G=A.5V(A.G,G);if(A.G.bs)A.2v=$j(A.G.bs);if(A.G.8O)A.1e=$j(A.G.8O);if(A.G.1J)A.3C=$j(A.G.1J);if(A.G.8T)A.3A=$j(A.G.8T);if(A.G.5K)A.4a=$j(A.G.5K);if(A.G.bu)A.2R=$j(A.G.bu);if(A.G.6o)A.4S=$j(A.G.6o);if(A.2v&&!A.2v.K)A.2v=12;if(A.1e&&!A.1e.K)A.1e=12;if(!A.2v&&A.1e)A.2v=A.1e.fQ(\'3f\');if(A.2v){if(!A.1e)A.1e=A.2v.8S(\'8O\');if(!A.3C)A.3C=A.2v.8S(\'1J\');if(!A.3A)A.3A=A.2v.8S(\'8T\')}if(A.1e&&!A.1e.K)A.1e=12;if(A.3C&&!A.3C.K)A.3C=12;if(A.3A&&!A.3A.K)A.3A=12;if(A.4a&&!A.4a.K)A.4a=12;if(A.2R&&!A.2R.K)A.2R=12;if(A.4S&&!A.4S.K)A.4S=12;if(A.1e&&A.3C)A.4b=J};A.6a=B(){A.2Y()};A.4D=B(){A.2t()};A.bG=B(1G){A.1G=1G;A.2Y()},A.2Y=B(){if(!A.4b)E;if(A.8E)E;A.8E=J;A.ch();F W=A;F L=A.3t();$1S({U:L,1c:\'X\',2F:B(o){W.bw(o)},2l:V})};A.bw=B(X){F W=A;A.1I=1b(X)?X:$32.4Z(X);A.1a=A.1I.2O(\'F\');A.4I=A.1I.8Y(A.G.c4);F 1r=A.1a.v(\'1o\');A.ct();if(1r==\'2B\'){F g9=A.G.8V?A.G.8V.1s(\',\'):[];A.1e.1T(\'\');A.4I.2M();O(F i=1;i<=A.4I.8L();i++){F 1u=A.4I.2O();F c2=(i+1)%2+1;1u.1y(\'ga\',c2);1u.1y(\'gA\',i);1u.bX(A.G.8V);if(A.G.bW)1u.1y(\'bW\',1Z(\'/3b/31/66.8H\',\'bZ\',\'[1Q:bR]\'));if(A.G.66)1u.1y(\'66.U\',1Z(\'/3b/31/66.8H\',\'bZ\',\'[1Q:bR]\'));if(A.G.4X)1u=A.G.4X(1u);if(A.4X)1u=A.4X(1u);F 2n=A.8F(1u);F 3N=$(2n).8M(A.1e);if(A.G.3q)A.G.3q(3N,1u);if(A.3q)A.3q(3N,1u);A.4I.6z()}if(A.4I.8L()<1){F 2n=A.3A?A.3A.1T():\'\';if(!2n){F 8B=A.1e.8C(\'8D\');if(8B)2n=\'<3f 5O="gH \'+(A.1e.8C(\'8D-21\')||\'gG\')+\' \'+(A.1e.8C(\'8D-1o\')||\'5v\')+\'"><p><em></em><i>\'+8B+\'</i></p></3f>\'}if(2n){F c7=$(2n);if(A.1e.is(\'gu\')&&c7.2w(\'1t > 1k\').K<1){2n=\'<1t><1k 4O="\'+A.2v.2w(\'fB eZ\').K+\'" ew>\'+2n+\'</1k></1t>\'}A.1e.3y(2n)}}if(A.G.6f)A.G.6f(A.1e,A.1a,A.1I);if(A.6f)A.6f(A.1e,A.1a,A.1I);A.3U(A.1e);if(A.4a){4c.5K.4V(A.1a,A.4a,B(1G){W.bG(1G)},{eF:J,eL:J});A.3U(A.4a)}if(A.cL)A.cL(A.1a)}R{if(!1r)1r=\'!fm\';F 6w=A.1a.v(\'2N\');if(!6w)6w=\'[\'+1r+\']\';4c.fy(\'5v\',6w,J);if(A.G.cm)A.G.cm(1r)}A.8E=V};A.8F=B(1u){F C=\'\';C=A.3C.1T();C=A.cq($(C),1u).fv();if(A.4P)C=A.4P($(C),1u).1T();C=$8K.4X(C,1u);E C};A.cq=B(3N,1u){F W=A;3N.2w(\'[3g-4P]\').4T(B(){F jo=$(A);F 1q=r(jo.1v(\'3g-4P\'),\'{2b}\',1u.v(jo.1v(\'3g-4P-1H\')));W.ce(3N,jo,1q)});E 3N};A.ce=B(3N,jo,1q){F 2n=A.2v.2w(\'fa[3g-4P="\'+1q+\'"]\').1T();if(2n)jo.3y(2n)};A.3U=B(jo){if(3h.3U)3h.3U(jo);if(A.G.3U)A.G.3U(jo)};A.ch=B(){if(A.2R){if(!A.2R.8I(\'.6o\')){A.2R.1T(A.8G())}A.2R.5Q();A.1e.1T(\'\')}R{A.1e.1T(A.8G())}};A.ct=B(){A.1e.1T(\'\');if(A.2R)A.2R.6k()};A.8G=B(){F C=\'\';if(A.4S)C=A.4S.1T();if(!C)C=\'<3K 5O="6o"><35 3X="/3b/6n/2t/ff.8H" /></3K>\';E C}};',62,1273,'||||||||||||||||||||||||||||||||||||this|function|re||return|var|opt|||true|length|_url||break|for|_data|case|else||new|url|false|that|xml||VDCS||_p|null||func|xcml|node|push||_x|treeVar|iso|value|name|jcont|oj|rea|_i|replace|_msg|td|values|_row|Date|status|dir|selector|_status|split|tr|treeItem|attr|params|typeof|addItem|p2|try|s1|tips|_Dom|catch|_count|page|field|maps|tpl|date|unit|dcs|_isObject|ardata|str|item|treeo|ajax|html|reTree|len|_d|_value|_v|rd||type|width|ise||ox|ary||_name|_list|inp|val|fields|Array|indexOf|callback|px|toString|tg|form|_n|error|height|_html|p1|channel|strer|jbody|body|load|idx|jwrap|find|jformitem|test|text|copy|succeed|jsubmit|tree|_col|ready|jform|undefined|switch|jfield|mi|pages|begin|message|getItemTree|sRow|send|jloader|utilTree|alert|nuller|link|join|substr|parse|s2||ua|util|_a|_k|img|getItem|ars|_ObjectType||wid|images|oTable|toi|Object|div|data|app|default|ps|len2|toConvert|arguments|1000|init|input|bind|getObjectType|Math|getURL|add|table|pattern|codes|append|isInt|jtple|_NodeItemNow|jtpl|_days|syb|_NodeItemLength|submit|time|timer|tl|span|agent|_ischeck|jitem|script|od|action|utilTable|object|isTree|uio|css|key|src|toReplaceRegex|method||local|get|_var|async|fielda||disabled||getMessage|jpaging|isinit|ui|tmpItemArray|parseInt|serveVar|reTable|extend|num|tmpAry|hour|err|utilXCML|day|events|_nodes|_Data|exec|toHTML|_field|scroll|event|configure|count|getConfigure|string|screen|BR|query|reset|v2|sMode|diff|txt|tableList|obj|ConfigureField|isa|href|lng|colspan|refill|substring|_r|jloading|each|RegExp|parser|minute|filterItem|include|toMapByXML|style|_queue|submitSet|alen|apd|ver|getItemValue||on|setValue|cookie|opt2|getValue|isf|dbg|left|_NodeItem|doItemBegin|doItemMove|responseXML|sTit|_dt|jsono|min|version|vars|_ary|_timer|responseText|end|_send|info|interval|toPaddedString|pageNum|querys|submit_status|jtips|ActiveXObject|_y|_node|alt|iev|x1|getDomObject|reMap|paging|formCheck|utilMap|url_back|class|extendo|show|getTime|already|browser|fmt|_opt|_id|serveURL|no|serve||loadXML|http|themes|loadResponseXML|getCount|avatar|addParam|now|getArray|refresh|getItemCount|toDate|EXT|_key|binds|ton|setFields|target|getElementsByTagName|hide|deep|options|common|loading|getNodeTree|toEncode|tmpRow|_page|round|emoney|XCML_NODENAME|_message|getNodeObject|tmpCol|move|_vcheck|scrollTop|PATTERN_VAR|embed|location|position|PATTERN_PRE|ConfigureNode|align|strc|title|keyCode|ret|items|charset|mod|charCodeAt|doParse|linktop|jforms|or|points|resize|prefix|box|doBegin|rc|_isload|pairs|exp|backurl|urlBack|epr|dotPos|focus|tim|_date|_func|_isa|doAppendTree|getItemKey|doMove|isData|setItem|zA|toNumber|getFullYear|getMonth|getDate|setTime|today|max|isn|toTableByXML|rRE|getHours|getMinutes|month|year|_blank|random|_load|second|total|getSeconds|millisecond|rgx|isNum|iss|xmlFooter|xmlHeader|nodes|opacity|color|hidden|overflow|right|extractJsoni|toXML|fname|slen|Z0|lt|parseFloat|pcn|match|dates|rel|charAt|eString|debug||msg|XMLHTTP|ws|bindEvent|initer|scrollLeft|xmlHttp|uri|goback|mini|vers|arys|isdebug|isTable|hei|open|getXMLHttpObject|statusMessage|statusOpt|def|statusParser|forms|frm|content|submit_value|serv_vars|toReplaceVar|of|msie|safari|months|prototype|sDate|_empty|attrd|empty|isparse|getItemString|getLoadString|gif|finder|Conversion|dtml|row|appendTo|file|cont|navigator|_back|chrome|finde|tple|scriptd|jsonFields|ext|rewrite|getItemTable|issend|hint|VCheck|_isexec|_NodeConfigure|macromedia|getConfigureField|doParseNode|doParseItem|afield|map|execItem|com|www|router|nodeValue|filter|firstChild|tagName|childNodes|middle|_e|flash|shockwave|application|getNodeValue|req|tableData|number|formCheckExtend|linkURL|put|isLogin|implementation|aryObj|pluginspage|boolean|3600|mozilla|urlmode|urlMode|setExt|times|base|100|opera|firefox|Lnegth|mm|webkit|ss|yyyy|weekdays|unescape|86400|isValid|365|trim|d_1|support|getflashplayer|editor|top|May|lang|relative|emotes|account|toFlash|toJSON|instanceof|NEWLINE|NaN||STime|0123456789|upload|d_2|60000|True|passport|index|_initURL|_no||__all|__no|oo|_sbt|_smt||126|ojo|x00|xff|ins|oxml|toLowerCase|padding|_rst|MSX|console|POST|border|_ready|mime|overrideMimeType|qi|000|ne|bindi|5px|CHARSET|vrt|realtime|write|_Top|floor|initURL|x2|block|200|while|dirn|display|readyState|prober|referrer|domain|ta|yes|XMLHttpRequest|getVars|clearInterval|clearTimeout|head|sidebar|_Left|cssText|wi|toCuted|setURL|wh|apos|scrollHeight|toJS|quot|tmpExpires|tit|click|names|createDocument||wrap|toTreeByXML|loader|array|parseAsync|tips_timer|submit_succeed|submit_ing|keypress|autoenter|select|_selector|getFields|opts|clickPage|getServeURL|Error|DTML|filterURL|servURL|Microsoft|PATTERN_FLAG_LABEL|itip|getXML|resolveExternals|uuid|_NodeItemArray|getConfigureNode|toReplacePre|dat|user_avatar|extractJson|message_|uid|tips_cover|s3|oe|__initer|node_table|submitInit|toEncodeFilterValue|jempty|s5|toReplaceEncodeFilter|PATTERN_LEBEL_ITEMS|tips_speed|isiniter|isItem|refillei|sendSucceed|toDecodei|loadon|parserTips|delItem||sendAsync|fails|vmin|ing|URL|refille|serv_method|getFieldArray|loadoff|tips_succeed|atts|code|tips_status|getI|error_message|isSucceed|post|callerror|parserAsync|back|getValueArray|checked|encrypt_timer|encrypt|encrypt_field|onerror|parsePaging|Page|list|radio|submitLock|isCheck|md5|getValues|_min|check|slice|removeClass|lastIndexOf|blur|timeout|debugxml|||setTimeout|confine|addClass|_placeholder|innerWidth|pageXOffset|Msxml|pos|formcheck||||innerHeight|pageYOffset|placeholder|Msxml2|getXMLHttp|bindChange|window|prompt||doSubmit|doFocus|selected|noEnter|which||300|submitOnce|tips_type|tips_msg|fieldValue|jquery|_type|checkbox|_initer|option||message_back|message_succeed|message_formcheck|isInputInt|toState|states|frm_post|message_serve|message_error_unknown|SELECT_INPUT_NAME|ctrlKey|submitQuick|message_parser|altKey|toConvertInput|doSubmitOnce|_o|element|addFavorite|external|speed|addPanel|debug_xml|fav|addFav|appendChild|gotop|setInterval|0px|history|cover|reload|tip_class|Height|confirm|isClickReturn|linkPlace|formHide|parent|servVar|document|resizable||||toolbar|tip|off|directories|scrollbars|menubar|root|gecko|aaa||rs|rv|isPrototypeOf|sl|sr|strl|toNum|limit|too|isNumber|tob|toBool|toInt|jump|strr|inps|Aug|Jul|Jun|Sep|Oct|Dec|Nov|Apr|Mar|s2o|o2s|th|sh|Feb|Jan|isNume|isInte|604800000|Try|apply|86400000|3600000|xmp|toFormat|initialize|create|continue|bar|nodeType|clone|Class|addEvent|getWeekDay|getDay|XCML|ish|Function|Hash|tn|isnum|isint|isb|isun|outerHTML|distance|distances|popups|strip|String|thead|January|getDir|setDir|IMG_PATH|setFile|nav|appVersion|userAgent|setUA|setr|setPath|EXTS|theme|_base|parents|setUnit|setServeVar|w3c|ieo|MinorVer|MajorVer|ie6|ie7|error_unknown|ie8|MSIE|openDatabase|CSS1Compat|compatMode|tips_status_|close|MessageEvent|popup|jsonFielda|_oe_|coin|December|November|price|money|prestige|strength||October|September|March|February|April||June|August|July|sTime||tbody|UNKNOWN|gray|EMPTY_REPLACERS|unknown|_t|_sn_|setv|EMPTY_REPLACER|CONTENT_TYPE_XML|php|config|inline|iblank|CONTENT_TYPE|utf|protocol|onError|explain|remark|s4|PATTERN_LEBEL_ITEM|PATTERN_LEBEL_DATA|PATTERN_LEBEL_DATAS|to_|cut|queryn|queryi|toXMLMap|leni|u00ff|u0000|toTXT|amp|toPrice|PATTERN_FLAG_STR|PATTERN_FLAG_OPTION|toCommaK|PATTERN_FLAG_CONTENT|PATTERN_FLAG_CONTENT2|PATTERN_VAR_PX|PATTERN_LEBEL_VAR|PATTERN_LEBEL_DAT|PATTERN_LEBEL_DATS|PATTERN_LEBEL_VARS|toFilterText|toTemplat|model|log|||col|margin|getCol|getRow|10px|JSON|bold|weight|doItemEnd||20px|ibegin|auto|font|008080|backgroundColor|FFF|bottom|fixed|zIndex|prepend|getLength|doEnd|toUpperCase|radius|getItemNum|vn|line|vi|getItemInt|PATTERN_FLAG_PARAMQ|PATTERN_FLAG_PARAMS|onload|pg|complete|valueOf|toDateDiff|call|paging_url|ms|newTable|newMap|newTree|toStringQuick|paging_num|toDateAdd|Image|_total|encodeURIComponent|toEncodei|isSafe|regEx|hlink|ftp|encodeURI||||||toDecode||360000||decodeURI|decodeURIComponent||toMinuteString|nn|timec|newXCML||SWAP_SYMBOL|3650||BATCH_SYMBOL|expires|toGMTString|toMap|PATTERN_FLAG|week|getRandom|getRandNum|PATTERN_FLAG_PARAM|PATTERN_FLAG_LABELS|PATTERN_FLAG_VAR|_|path|toTreeByString|getNow|__node|XCML_NODE_NAMES|XCML_NODE_NAME_CONFIGURE|isMap|isXCML|getToday|XCML_NODE_EXIST|NodeValue|escape|getValueGipId|XCML_NODE_CONNECT_SYMBOL|__yes|iend|concat|setVar|high|flashvars|classid|imove|clsid|quality|setLinkURL|resete|doPop|Flash|param|movie|addVar|AE6D|11cf|parentNode|cab|cellpadding|cellspacing|wmode|absolute|swflash|cabs|444553540000||96B8|codebase|download||pub|textarea|vals|error_msg|nodata|304|statusSucceed|nResult|nError|noexist|nopermission|onLoad|GET|linkr|onReady|failed|onreadystatechange|Send|tmapsByXML|set|_encrypt_timer|minlength|elementv|QueuesLoader|QueueAsync|Queues|Content|setRequestHeader|Type|urlencoded|Missing|opaque|D27CDB6E|pointer|cursor|doInit|doShow|XMLDOM|documentElement|loadFile|DOMParser|loadURL|background|cdeaf6|oString||getItemArray|getItemValueInt|Row|setItemValue|api|attri|ivi|Col|ivn|otree|otable|getItemValueNum|moz|isObject|getItemLength|getItemNow|ownerDocument|parseFromString|getItemField|alpha'.split('|'),0,{}))

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
	class_pop:'gray',
	parser:function(obj,opt){
		if(typeof opt !='object'){
			this.type=opt;
		}else{
			this.type=opt.type;
		} 
		if(!this.type) this.type='checkbox';
		this.obj=obj;
		this.opt=opt;
		if(this.obj.find('input').length>6) return;
		var opn_html=this.getOpnHtml();
		this.obj.after(opn_html);
		this.bindAction();
		this.obj.hide();
	},
	getOpnHtml:function(){
		var that=this;
		var htmla=[];
		htmla.push('<div class="opn-group" data-toggle="buttons-'+that.type+'">');
		this.obj.find('input').each(function(){
			var jthis=$(this);
			var id=jthis.attr('id');
			var text=jthis.parent('label').text();
			if(!text) text=jthis.next('span').text();
			if(!text) text=jthis.attr('value');
			var ischecked='';
			if(jthis.checked()) ischecked='opn-'+that.class_pop+'';
			htmla.push('<a type="button" class="opn '+ischecked+'">'+text+'</a>');
		});
		htmla.push('</div>');
		//$('.myval').after(htmla.join(''));
		return htmla.join('')
	},
	bindAction:function(){
		var that=this;
		this.opns=this.obj.next('.opn-group');
		this.opns.find('.opn').each(function(i){
			if($(this).attr('isinit')) return;
			if(that.type=='checkbox'){
				$(this).bind('click.check',function(){
					if($(this).hasClass('opn-'+that.class_pop+'')){
						that.getOrigWrap($(this)).find('input:eq('+i+')').checked(false);
					}else{
						that.getOrigWrap($(this)).find('input:eq('+i+')').checked(true);
					}
				}).attr('isinit','yes');
			}else if(that.type=='radio'){
				//that.getOrigWrap($(this)).find('input').checked(false)
				$(this).bind('click.check',function(){
					var jinput=that.getOrigWrap($(this)).find('input');
					jinput.checked(false);
					jinput.end().find('input:eq('+i+')').checked(true);
					//alert(i);
				}).attr('isinit','yes');
			}
			
		});
	},
	getOrigWrap:function(jthis){
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

	init:function(jwrap){
		var that=this;
		if(jwrap){
			jwrap.find('[data-ui-form]').each(function(){
				var jthis=$(this);
				if(jthis.attr('data-ui-form')) that.parser(jthis,jthis.attr('data-ui-form'));
			});
		}
		this.initOpnAction()
	},
	initOpnAction:function(){
		var that=this;
		$(document).on('click.button.data-api', '[data-toggle^=button]', function (e) {
			var $opn = $(e.target);
			if (!$opn.hasClass('opn')) $opn = $opn.closest('.opn');
			that.switchopn($opn,'toggle');
		})	
	},
	switchopn:function(obj,option){
		var that=this;
		return obj.each(function(){that.toggleopn(obj)})
	},
	toggleopn:function(obj){
		var that=this;
		var $parent = obj.closest('[data-toggle="buttons-radio"]')
		$parent && $parent.find('.opn-'+that.class_pop+'').removeClass('opn-'+that.class_pop+'');
		obj.toggleClass('opn-'+that.class_pop+'')
	},
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

