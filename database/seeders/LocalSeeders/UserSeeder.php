<?php

namespace Database\Seeders\LocalSeeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Users from prod dump (2025-12-10) + local dev admin.
     * Legacy role_id mapping applied (4,7,8,9,12,16 → consulta | 11 → admin | 14 → autorizador).
     * Users without legacy role → inactivo.
     *
     * @var array<int, array{id:int,name:string,email:string,hash:string|null,roles:array<int,string>}>
     */
    protected array $users = [
        // Local dev admin
        ['id' => 1000, 'name' => 'Angel Jimenez', 'email' => 'ajimenezescobar@gmail.com', 'hash' => null, 'roles' => ['admin']],
        // Imported from prod
        ['id' => 42, 'name' => 'JAIRO ALFONSO CEBALLOS POLANCO', 'email' => 'cpja@novonordisk.com', 'hash' => '$2y$10$wdjgzFAHIb0.ST6zKl.Clua75hP0xHZuA3ad/BCU6.h8GI3v8rSki', 'roles' => ['inactivo']],
        ['id' => 16, 'name' => 'RUTH RODRIGUEZ', 'email' => 'rthr@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 3, 'name' => 'THOMAS ANSBJOERN LORENZEN', 'email' => 'thlz@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 26, 'name' => 'Mauricio Casteblanco Maradiaga', 'email' => 'mqcm@novonordisk.com', 'hash' => '$2y$10$P3mty3es5qj2Y2.ohVAj7uV2lssgw7KVf2DcaSAuUuQ7CNYSMClEq', 'roles' => ['consulta']],
        ['id' => 14, 'name' => 'JUAN CARLOS ARIAS NARVAEZ', 'email' => 'jcnv@novonordisk.com', 'hash' => '$2y$10$8PY0o19XDxhWrBbg2x.Az.c7Y3/eWztrdVdHQQpj8omrEtiZpxVB.', 'roles' => ['inactivo']],
        ['id' => 45, 'name' => 'ANA CAROLINA RUIZ MORA', 'email' => 'acnm@novonordisk.com', 'hash' => '$2y$10$sBAvD/OAuj7WnYzQTAl4QugUK21MEqVHm3shDmcPULLtFBK6w2pAq', 'roles' => ['inactivo']],
        ['id' => 47, 'name' => 'LINA MARIA LOPERA BECERRA', 'email' => 'lmab@novonordisk.com', 'hash' => '$2y$10$I8NNk8NVbajpTBGcIqYRXOpi5jv3VaUPmCEQSAxXhdcP.155.3jPG', 'roles' => ['inactivo']],
        ['id' => 2, 'name' => 'NOVOS', 'email' => 'novo@novonordisk.com', 'hash' => '$2y$10$mC2UHZri.WkQ1whoh9wNB.7DFy.o8H2fAb73EQGEih9y0Y7XgM0Bq', 'roles' => ['inactivo']],
        ['id' => 32, 'name' => 'CAMILA VALERO PARDO', 'email' => 'cvap@novonordisk.com', 'hash' => '$2y$10$rULLPZEfI9TJNOAtU5bRIeeoqwwA6dzLGwbaqwY49QBTy95RkaIQe', 'roles' => ['inactivo']],
        ['id' => 48, 'name' => 'CLAUDIA MARCELA CORTES IBARRA', 'email' => 'cmci@novonordisk.com', 'hash' => '$2y$10$JiW4FGHWX3eSLLXh6DW38utCl2o0uwBkJmDxjMdMplZsPvvpaEMJ6', 'roles' => ['inactivo']],
        ['id' => 46, 'name' => 'DIANA MARGARITA GUTIERREZ NAVAS', 'email' => 'dmgn@novonordisk.com', 'hash' => '$2y$10$A17dy8bo8oHl1DM/Trj/Du/0mnjfTLmJLlQMGVHy3TGC4I.Qecpj6', 'roles' => ['inactivo']],
        ['id' => 44, 'name' => 'DIEGO ANDRES GARZON CALDERON', 'email' => 'dgcl@novonordisk.com', 'hash' => '$2y$10$F/k9jR.G2j0/bjTzEyjie.vuB4ksGYWiF.Ic23OegELv9.07wBowK', 'roles' => ['inactivo']],
        ['id' => 34, 'name' => 'MARIA JOSE MONROY VICTORINO', 'email' => 'mjmv@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 25, 'name' => 'MIGUEL ANGEL GARCIA', 'email' => 'xmgb@novonordisk.com', 'hash' => '$2y$10$K671l8ZCSEuv4C053g/Vo.yoDzAhxAKer7hUJCxfux0WQxhlIH.tW', 'roles' => ['inactivo']],
        ['id' => 33, 'name' => 'PAOLA CONSTANZA NOGUERA CRUZ', 'email' => 'pacz@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 28, 'name' => 'Judith Sanchez Canon', 'email' => 'jtca@novonordisk.com', 'hash' => '$2y$10$7IcUIPMPN6ce0kUG4LuTj.81q8fD7yOMtpmDk.xzkj7BA3vXEmwDS', 'roles' => ['consulta']],
        ['id' => 18, 'name' => 'MIGUEL IGNACIO MORALES BUELVAS', 'email' => 'mbuv@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 10, 'name' => 'PREAUTORIZADOR', 'email' => 'preautorizador@novo.com', 'hash' => null, 'roles' => ['inactivo']],
        ['id' => 53, 'name' => 'LUZ ALEYDA HERNANDEZ VARGAS', 'email' => 'lalv@novonordisk.com', 'hash' => '$2y$10$0EXTDJobTfkJz1XqsTEPZeKM5yZ6VIykw/icFIAbJL1LJcfRiTT3i', 'roles' => ['autorizador']],
        ['id' => 27, 'name' => 'MARVIC DEL VALLE RODRIGUEZ OROZCO', 'email' => 'mozo@novonordisk.com', 'hash' => '$2y$10$E8jFKpmWKJNm3ORd3L1nk./jqkB8fmD50GeCQOe4qc9DdNt0Himfe', 'roles' => ['inactivo']],
        ['id' => 12, 'name' => 'AUDITOR COTIZACIONES', 'email' => 'cotizaciones@novo.com', 'hash' => '$2y$10$lT89LxkfBU/hjGtlw7iYuO9OrsyCvQlioD83ugOqRQaPsYaHlAXzm', 'roles' => ['inactivo']],
        ['id' => 7, 'name' => 'Patricia Hernandez Ariza', 'email' => 'phna@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['consulta']],
        ['id' => 1, 'name' => 'MOLE INTERACTIVE', 'email' => 'devs@moleint.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 51, 'name' => 'NELLY ASTRID MORENO', 'email' => 'neom@novonordisk.com', 'hash' => '$2y$10$P.2ZHkp71j8.PVeM.7jnTO13QOzoc94XDWJorS.S0N16ANKtcK8.a', 'roles' => ['inactivo']],
        ['id' => 54, 'name' => 'PATRICIA FIELD DE LEON', 'email' => 'pfdl@novonordisk.com', 'hash' => '$2y$10$R1YTf.tAb9KKFxY0ZHzpPu4OrKHxKqgJ8C5w6jTi0uwtHcJ7YBvgm', 'roles' => ['autorizador']],
        ['id' => 31, 'name' => 'Romero Beltran Fernney', 'email' => 'qrfy@novonordisk.com', 'hash' => null, 'roles' => ['consulta']],
        ['id' => 30, 'name' => 'INGRID YASBLEYDY OCHOA', 'email' => 'inyo@novonordisk.com', 'hash' => '$2y$10$3Fb3jHsXOnpfOyIxDvuB4e8qP7rvU8dBVTmZtR.L8qBP5JYEePZZG', 'roles' => ['consulta', 'cam', 'admin_venta']],
        ['id' => 22, 'name' => 'CAMILO MANJARRES', 'email' => 'cmnm@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['adminprecios', 'admin_venta']],
        ['id' => 43, 'name' => 'Claudia Juri Savino', 'email' => 'cjsa@novonordisk.com', 'hash' => '$2y$10$NY5fHits9tM/E33MDJ2cmu.TBV9SVQfX1De/JrJddVXqwhL5NB/um', 'roles' => ['admin']],
        ['id' => 11, 'name' => 'AUDITOR', 'email' => 'auditor@novo.com', 'hash' => null, 'roles' => ['inactivo']],
        ['id' => 29, 'name' => 'JASBEIDY MURILLO', 'email' => 'jbmu@novonordisk.com', 'hash' => '$2y$10$A80WTcFpUxLQiAMIliHV8eE3UF7rkOk6nNXbdH4O.G5ZIKWX0MdXy', 'roles' => ['consulta']],
        ['id' => 50, 'name' => 'AUDITORIASCOLOMBIA', 'email' => 'auditoriascolombia@novonordisk.com', 'hash' => '$2y$10$XA6KW2u4txsEIqSB2brVKeEWmB/IqsqdrDBiDQbIhltc4Bm2su5D.', 'roles' => ['inactivo']],
        ['id' => 55, 'name' => 'Luis David Galarza Quintero', 'email' => 'ldzq@novonordisk.com', 'hash' => '$2y$10$ZY/iGCI17myAsW.pg1NDc.vqEHQHrc6Gy6lGLs670TwNwUXXHeEXq', 'roles' => ['consulta']],
        ['id' => 4, 'name' => 'GERARDO PRIETO ROJAS', 'email' => 'gepr@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 66, 'name' => 'ISAIAS RUIZ BELTRAN', 'email' => 'irzt@novonordisk.com', 'hash' => '$2y$10$QY.80xLCizW8jgln4KyhuuaH1e1byqriCU40H2tC55CbcVm40fiNu', 'roles' => ['cam']],
        ['id' => 52, 'name' => 'MANUEL JOSE DELGADO CARBALLO', 'email' => 'mjdc@novonordisk.com', 'hash' => '$2y$10$y07Gyr1tBdYy/0CNkmdJgOrZNdto5H4mkEc4GRsc8iUpLSyUZ5k/a', 'roles' => ['inactivo']],
        ['id' => 70, 'name' => 'MELISSA BARBERI', 'email' => 'qaei@novonordisk.com', 'hash' => '$2y$10$uNUZ8c5/wPPpq.YlaRKOLeHsfc8zvhYR60Y1aeNkRdlzUl1HC4zEe', 'roles' => ['admin']],
        ['id' => 62, 'name' => 'ANDRES DELGADO MONTEZUMA', 'email' => 'azdm@novonordisk.com', 'hash' => '$2y$10$ohRmLoEpQRMtQdBaIOWQK.Dx6GKk3kVgpAwWAHPQgiZVaUJPeNdUC', 'roles' => ['inactivo']],
        ['id' => 13, 'name' => 'Emerson David Arango Gaviria', 'email' => 'emdg@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['consulta']],
        ['id' => 58, 'name' => 'DIEGO JARAMILLO', 'email' => 'programador@interactiva.net.co', 'hash' => '$2y$10$nkAA4LeJ9A3yOb51KJQaIO.aWYP15Or5lfRM1w7n/mpcUzK.YiSJ.', 'roles' => ['inactivo']],
        ['id' => 59, 'name' => 'JOSE LUIS CALDAS PUERTO', 'email' => 'jlpq@novonordisk.com', 'hash' => '$2y$10$ClWXtnFj8BupJZXHVUzwzud9mFlnuBdCg.kxUX3hxpolsTYFseCTW', 'roles' => ['inactivo']],
        ['id' => 61, 'name' => 'MICHELLE LEON', 'email' => 'hmxl@novonordisk.com', 'hash' => '$2y$10$H2sdZ.7tPRmSVQt7xDXJF.sRyAOlthy/8qT31qTJOUrm9AtW5hnRq', 'roles' => ['consulta']],
        ['id' => 17, 'name' => 'MARITZA JARAMILLO SANCHEZ', 'email' => 'nmjs@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['inactivo']],
        ['id' => 24, 'name' => 'EDNA MARGARITA MUNOZ ARENAS', 'email' => 'vmgt@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['cam']],
        ['id' => 63, 'name' => 'CAROLINA ARBOLEDA GARCIA', 'email' => 'wcag@novonordisk.com', 'hash' => '$2y$10$LcJCLolJf5czG9yOG7TWyOxHts3vdStLczAftNmhPfmr5Z5JRH8A2', 'roles' => ['adminprecios', 'autorizador', 'consulta']],
        ['id' => 15, 'name' => 'ALEXANDER VARGAS OVIEDO', 'email' => 'avao@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['cam']],
        ['id' => 23, 'name' => 'SANDRA MILENA DIAZ SANMIGUEL', 'email' => 'zssg@novonordisk.com', 'hash' => '$2y$10$nuNHXUV1lSVUxa5mo1Vxc.K344Upj/ZHA/zsR3WdqpcYFeTbqtrlC', 'roles' => ['cam']],
        ['id' => 64, 'name' => 'DIEGOTEST2 DIEGOTEST2', 'email' => 'diegotest2@novonordisk.com', 'hash' => '$2y$10$ltHvqodxfqqYdEbLJKEsjO0B3Q7nvSDQGFtKdBs2EFhbtbn1LWyEm', 'roles' => ['inactivo']],
        ['id' => 57, 'name' => 'MARIA CONSTANZA PINZON BUENO', 'email' => 'mcpb@novonordisk.com', 'hash' => '$2y$10$miQzQ2wq6RQDnq12FNV7teaqhjdIMVHaZn8gj0Dw01krcy34EfQ6O', 'roles' => ['inactivo']],
        ['id' => 68, 'name' => 'MARIA PAULA BEJARANO', 'email' => 'mpgq@novonordisk.com', 'hash' => '$2y$10$5hnLm4.zAd28g.zCXIhnk.V/4qxC5Nfex.4oFKYOZ0y3SkE7wm6NC', 'roles' => ['admin_venta']],
        ['id' => 67, 'name' => 'VIVIANA ALVARADO', 'email' => 'iavo@novonordisk.com', 'hash' => '$2y$10$ljj794e2zwRnNC2nignppeO5oRkrPHLoLF/GK1/84qIphNCn2ZRZO', 'roles' => ['cam']],
        ['id' => 69, 'name' => 'CAMILO MORENO KNUDSEN', 'email' => 'cmzk@novonordisk.com', 'hash' => '$2y$10$PeMrYSen4XTrvBpmpmlT0OXKcjhSfeKOpaYTNepDp/wRDDvW7Z1Uy', 'roles' => ['admin_venta']],
        ['id' => 56, 'name' => 'LUIS FERNANDO MARTINEZ CAMACHO', 'email' => 'lfcz@novonordisk.com', 'hash' => '$2y$10$TdXwQfd2pv17uYxVre.Ut.iy41PG699LsQm7fPWpJ5nVNC3dTn6S.', 'roles' => ['consulta', 'cam', 'admin_venta']],
        ['id' => 71, 'name' => 'NATALIA MERCEDES SERRANO ARIZA', 'email' => 'nvay@novonordisk.com', 'hash' => '$2y$10$RFsS7R5OwlgoFOZo0Cr4p.utU15LNM9rL.aVa7FXULrP1mZE3GBdm', 'roles' => ['autorizador']],
        ['id' => 65, 'name' => 'BRAYAN JOSE CASTRO ORTIZ', 'email' => 'byor@novonordisk.com', 'hash' => '$2y$10$CcHOgd2c3CGlGqp3tpwB6uOCSzEEU5OUBHbmtHTkjshno8BTPTAp.', 'roles' => ['inactivo']],
        ['id' => 49, 'name' => 'DIEGO FERNANDO BURITICA LOAIZA', 'email' => 'dfbl@novonordisk.com', 'hash' => '$2y$10$J8Cdyr3i3SjERgA/b.eleujcC2DVg.6KblI5kLaatvPKsOXeqTiGq', 'roles' => ['inactivo']],
    ];

    public function run(): void
    {
        $fallback = Hash::make('password');

        Schema::disableForeignKeyConstraints();
        DB::table('model_has_roles')->where('model_type', User::class)->delete();
        DB::table('model_has_permissions')->where('model_type', User::class)->delete();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($this->users as $data) {
            $user = new User();
            $user->id                = $data['id'];
            $user->name              = $data['name'];
            $user->email             = $data['email'];
            $user->password          = $data['hash'] ?? $fallback;
            $user->email_verified_at = now();
            $user->save();

            $user->syncRoles($data['roles'] ?? []);
        }
    }
}
