<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 13/05/18
 * Time: 17:31
 */
namespace TS\NaoBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Entity\Observation;
use TS\NaoBundle\Entity\TAXREF;
use TS\NaoBundle\Enum\ProfilEnum;
use TS\NaoBundle\Enum\StateEnum;

class ObservationFixture implements FixtureInterface {

    public function load(ObjectManager $manager)
    {
        //Fixture #1
        //$this->loadObservation1($manager);

        //Fixture #2
        $this->loadObservation2($manager);

        //Fixture #3
        $this->loadObservation3($manager);
    }

    private function loadObservation1(ObjectManager $manager)
    {
        //user #1
        $user1 = new User();
        $user1->setName("user1");
        $user1->setSurname("Tester1");
        $user1->setUsername("Amateur1");
        $user1->setEmail("user1.tester1@gmail.fr");
        $user1->setPassword("pwdToTest1");
        $user1->setRoles(ProfilEnum::BIRD_FANCIER);

        $manager->persist($user1);

        //User #1 Observation
        $User1Obs = new Observation();
        $User1Obs->setDtCreation(DateTime::createFromFormat("Y-m-d H:i:s", "2018-02-11 10:20:15"));
        $User1Obs->setTitle("Premiere observation d'une Pénélope à gorge bleue");
        $User1Obs->setSpecimen("Pénélope à gorge bleue, Pénélope siffleuse");
        $User1Obs->setNbSpecimen(1);
        $User1Obs->setLatitude(43.918466289752644);
        $User1Obs->setLongitude(2.145517385159011);
        $User1Obs->setRemarks("Quam ob rem vita quidem talis fuit vel fortuna vel gloria, ut nihil posset".
            " accedere, moriendi autem sensum celeritas abstulit; quo de genere mortis".
            " difficile dictu est; quid homines suspicentur, videtis; hoc vere tamen licet dicere, P. Scipioni ex".
            " multis diebus, quos in vita celeberrimos laetissimosque viderit, illum diem clarissimum fuisse, cum ".
            "senatu dimisso domum reductus ad vesperum est a patribus conscriptis, populo Romano, sociis et Latinis, ".
            "pridie quam excessit e vita, ut ex tam alto dignitatis gradu ad superos videatur deos potius quam ad ".
            "inferos pervenisse.");
        $User1Obs->setState(StateEnum::VALIDATE);

        // Taxref specimen associated to
        $taxrefRepo = $manager->getRepository("TSNaoBundle:TAXREF");
        $taxref1 = $taxrefRepo->find(442237);
        $User1Obs->setTaxref($taxref1);
        $user1->addObservation($User1Obs);
        $manager->persist($User1Obs);

        $manager->flush();
    }

    private function loadObservation2(ObjectManager $manager)
    {
        //user #2
        $user1 = new User();
        $user1->setName("user2");
        $user1->setSurname("Tester2");
        $user1->setUsername("Amateur2");
        $user1->setEmail("user2.tester2@gmail.fr");
        $user1->setPassword("pwdToTest2");
        $user1->setRoles(ProfilEnum::BIRD_FANCIER);

        $manager->persist($user1);

        //User #1 Observation
        $User1Obs = new Observation();
        $User1Obs->setDtCreation(DateTime::createFromFormat("Y-m-d H:i:s", "2017-10-11 14:20:15"));
        $User1Obs->setTitle("un groupe de Pénélopes à gorge bleue");
        $User1Obs->setSpecimen("Aburria pipile");
        $User1Obs->setNbSpecimen(3);
        $User1Obs->setLatitude(43.359399);
        $User1Obs->setLongitude(-1.76614799999993);
        $User1Obs->setRemarks("Quam ob rem vita quidem talis fuit vel fortuna vel gloria, ut nihil posset".
            " accedere, moriendi autem sensum celeritas abstulit; quo de genere mortis".
            " difficile dictu est; quid homines suspicentur, videtis; hoc vere tamen licet dicere, P. Scipioni ex".
            " multis diebus, quos in vita celeberrimos laetissimosque viderit, illum diem clarissimum fuisse, cum ".
            "senatu dimisso domum reductus ad vesperum est a patribus conscriptis, populo Romano, sociis et Latinis, ".
            "pridie quam excessit e vita, ut ex tam alto dignitatis gradu ad superos videatur deos potius quam ad ".
            "inferos pervenisse.");
        $User1Obs->setState(StateEnum::VALIDATE);

        // Taxref specimen associated to
        $taxrefRepo = $manager->getRepository("TSNaoBundle:TAXREF");
        $taxref = $taxrefRepo->find(645127);
        $User1Obs->setTaxref($taxref);
        $user1->addObservation($User1Obs);
        $manager->persist($User1Obs);

        $manager->flush();
    }

    private function loadObservation3(ObjectManager $manager)
    {
        //user #3
        $user1 = new User();
        $user1->setName("user3");
        $user1->setSurname("Tester3");
        $user1->setUsername("Naturalist1");
        $user1->setEmail("user3.tester3@gmail.fr");
        $user1->setPassword("pwdToTest3");
        $user1->setRoles(ProfilEnum::NATURALIST);
        $user1->setGrade("Certifation");

        $manager->persist($user1);

        //User #3 ObservationS
        $User1Obs = new Observation();
        $User1Obs->setDtCreation(DateTime::createFromFormat("Y-m-d H:i:s", "2017-10-08 15:15:15"));
        $User1Obs->setTitle("Pénélope siffleuse - superbe");
        $User1Obs->setSpecimen("Crax cumanensis");
        $User1Obs->setNbSpecimen(1);
        $User1Obs->setLatitude(43.56473449540799);
        $User1Obs->setLongitude(-1.3205150654296176);
        $User1Obs->setRemarks("Quam ob rem vita quidem talis fuit vel fortuna vel gloria, ut nihil posset".
            " accedere, moriendi autem sensum celeritas abstulit; quo de genere mortis".
            " difficile dictu est; quid homines suspicentur, videtis; hoc vere tamen licet dicere, P. Scipioni ex".
            " multis diebus, quos in vita celeberrimos laetissimosque viderit, illum diem clarissimum fuisse, cum ".
            "senatu dimisso domum reductus ad vesperum est a patribus conscriptis, populo Romano, sociis et Latinis, ".
            "pridie quam excessit e vita, ut ex tam alto dignitatis gradu ad superos videatur deos potius quam ad ".
            "inferos pervenisse.");
        $User1Obs->setState(StateEnum::VALIDATE);

        // Taxref specimen associated to
        $taxrefRepo = $manager->getRepository("TSNaoBundle:TAXREF");
        $taxref = $taxrefRepo->find(780295);
        $User1Obs->setTaxref($taxref);
        $user1->addObservation($User1Obs);
        $manager->persist($User1Obs);

        //User #3 Observations 2
        $User1Obs2 = new Observation();
        $User1Obs2->setDtCreation(DateTime::createFromFormat("Y-m-d H:i:s", "2018-10-12 10:32:15"));
        $User1Obs2->setTitle("Autre Pénélope siffleuse En attente de validation");
        $User1Obs2->setSpecimen("Crax cumanensis");
        $User1Obs2->setNbSpecimen(1);
        $User1Obs2->setLatitude(43.56473449540799);
        $User1Obs2->setLongitude(-1.3205150654296176);
        $User1Obs2->setRemarks("Quam ob rem vita quidem talis fuit vel fortuna vel gloria, ut nihil posset".
            " accedere, moriendi autem sensum celeritas abstulit; quo de genere mortis".
            " difficile dictu est; quid homines suspicentur, videtis; hoc vere tamen licet dicere, P. Scipioni ex".
            " multis diebus, quos in vita celeberrimos laetissimosque viderit, illum diem clarissimum fuisse, cum ".
            "senatu dimisso domum reductus ad vesperum est a patribus conscriptis, populo Romano, sociis et Latinis, ".
            "pridie quam excessit e vita, ut ex tam alto dignitatis gradu ad superos videatur deos potius quam ad ".
            "inferos pervenisse.");
        $User1Obs2->setState(StateEnum::SUBMIT);

        // Taxref specimen associated to
        $User1Obs2->setTaxref($taxref);
        $user1->addObservation($User1Obs2);
        $manager->persist($User1Obs2);

        $manager->flush();
    }

    private function loadObservation4(ObjectManager $manager)
    {
        //user #4
        $user1 = new User();
        $user1->setName("user2");
        $user1->setSurname("Tester2");
        $user1->setUsername("Amateur2");
        $user1->setEmail("user2.tester2@gmail.fr");
        $user1->setPassword("pwdToTest2");
        $user1->setRoles(ProfilEnum::BIRD_FANCIER);

        $manager->persist($user1);

        //User #1 Observation
        $User1Obs = new Observation();
        $User1Obs->setDtCreation(DateTime::createFromFormat("Y-m-d H:i:s", "2018-04-22 16:45:15"));
        $User1Obs->setTitle("un groupe de Pénélopes à gorge bleue");
        $User1Obs->setSpecimen("Aburria pipile");
        $User1Obs->setNbSpecimen(3);
        $User1Obs->setLatitude(43.359399);
        $User1Obs->setLongitude(-1.76614799999993);
        $User1Obs->setRemarks("Quam ob rem vita quidem talis fuit vel fortuna vel gloria, ut nihil posset".
            " accedere, moriendi autem sensum celeritas abstulit; quo de genere mortis".
            " difficile dictu est; quid homines suspicentur, videtis; hoc vere tamen licet dicere, P. Scipioni ex".
            " multis diebus, quos in vita celeberrimos laetissimosque viderit, illum diem clarissimum fuisse, cum ".
            "senatu dimisso domum reductus ad vesperum est a patribus conscriptis, populo Romano, sociis et Latinis, ".
            "pridie quam excessit e vita, ut ex tam alto dignitatis gradu ad superos videatur deos potius quam ad ".
            "inferos pervenisse.");
        $User1Obs->setState(StateEnum::VALIDATE);

        // Taxref specimen associated to
        $taxrefRepo = $manager->getRepository("TSNaoBundle:TAXREF");
        $taxref = $taxrefRepo->find(645127);
        $User1Obs->setTaxref($taxref);
        $user1->addObservation($User1Obs);
        $manager->persist($User1Obs);

        $manager->flush();
    }

    private function loadObservation5(ObjectManager $manager)
    {
        //user #2
        $user1 = new User();
        $user1->setName("user2");
        $user1->setSurname("Tester2");
        $user1->setUsername("Amateur2");
        $user1->setEmail("user2.tester2@gmail.fr");
        $user1->setPassword("pwdToTest2");
        $user1->setRoles(ProfilEnum::BIRD_FANCIER);

        $manager->persist($user1);

        //User #1 Observation
        $User1Obs = new Observation();
        $User1Obs->setDtCreation(DateTime::createFromFormat("Y-m-d H:i:s", "2017-10-11 14:20:15"));
        $User1Obs->setTitle("un groupe de Pénélopes à gorge bleue");
        $User1Obs->setSpecimen("Aburria pipile");
        $User1Obs->setNbSpecimen(3);
        $User1Obs->setLatitude(43.359399);
        $User1Obs->setLongitude(-1.76614799999993);
        $User1Obs->setRemarks("Quam ob rem vita quidem talis fuit vel fortuna vel gloria, ut nihil posset".
            " accedere, moriendi autem sensum celeritas abstulit; quo de genere mortis".
            " difficile dictu est; quid homines suspicentur, videtis; hoc vere tamen licet dicere, P. Scipioni ex".
            " multis diebus, quos in vita celeberrimos laetissimosque viderit, illum diem clarissimum fuisse, cum ".
            "senatu dimisso domum reductus ad vesperum est a patribus conscriptis, populo Romano, sociis et Latinis, ".
            "pridie quam excessit e vita, ut ex tam alto dignitatis gradu ad superos videatur deos potius quam ad ".
            "inferos pervenisse.");
        $User1Obs->setState(StateEnum::VALIDATE);

        // Taxref specimen associated to
        $taxrefRepo = $manager->getRepository("TSNaoBundle:TAXREF");
        $taxref = $taxrefRepo->find(645127);
        $User1Obs->setTaxref($taxref);
        $user1->addObservation($User1Obs);
        $manager->persist($User1Obs);

        $manager->flush();
    }
}