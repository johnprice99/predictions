<?php

namespace EatSleepCode\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use EatSleepCode\APIBundle\Entity\Fixture;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/fixtures")
 * @Security("has_role('ROLE_ADMIN')")
 */
class FixtureController extends Controller {

    /**
     * @Route("/", name="fixture_list")
     * @Template
     */
    public function fixtureListAction() {
        return array(
            'fixtures' => $this->getDoctrine()->getRepository('EatSleepCodeAPIBundle:Fixture')->findAll()
        );
    }

    /**
     * @Route("/download", name="fixture_download")
     */
    public function downloadFixturesAction() {
//        $responseBody = '{"_links":[{"self":"http://api.football-data.org/alpha/soccerseasons/398/fixtures"},{"soccerseason":"http://api.football-data.org/alpha/soccerseasons/398"}],"count":10,"fixtures":[{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147075"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/66"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/73"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Manchester United FC","awayTeamName":"Tottenham Hotspur FC","result":{"goalsHomeTeam":1,"goalsAwayTeam":2}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147078"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/70"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/64"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Stoke City FC","awayTeamName":"Liverpool FC","result":{"goalsHomeTeam":0,"goalsAwayTeam":1}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147076"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/67"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/340"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Newcastle United","awayTeamName":"FC Southampton","result":{"goalsHomeTeam":0,"goalsAwayTeam":1}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147079"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/74"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/65"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"West Bromwich Albion","awayTeamName":"Manchester City FC","result":{"goalsHomeTeam":1,"goalsAwayTeam":2}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147074"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/338"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/71"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Leicester City","awayTeamName":"Sunderland AFC","result":{"goalsHomeTeam":1,"goalsAwayTeam":1}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147073"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/62"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/346"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Everton FC","awayTeamName":"Watford","result":{"goalsHomeTeam":1,"goalsAwayTeam":1}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147072"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/61"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/72"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Chelsea FC","awayTeamName":"Swansea City","result":{"goalsHomeTeam":0,"goalsAwayTeam":5}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147071"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/57"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/563"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"FC Arsenal London","awayTeamName":"West Ham United FC","result":{"goalsHomeTeam":3,"goalsAwayTeam":1}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147117"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/1044"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/58"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"AFC Bournemouth","awayTeamName":"Aston Villa FC","result":{"goalsHomeTeam":0,"goalsAwayTeam":0}},{"_links":{"self":{"href":"http://api.football-data.org/alpha/fixtures/147077"},"soccerseason":{"href":"http://api.football-data.org/alpha/soccerseasons/398"},"homeTeam":{"href":"http://api.football-data.org/alpha/teams/68"},"awayTeam":{"href":"http://api.football-data.org/alpha/teams/354"}},"date":"2015-08-08T14:00:00Z","status":"TIMED","matchday":1,"homeTeamName":"Norwich City","awayTeamName":"Crystal Palace","result":{"goalsHomeTeam":1,"goalsAwayTeam":0}}]}';
        $em = $this->getDoctrine()->getManager();
        $allTeams = $this->getDoctrine()->getRepository('EatSleepCodeAPIBundle:Team')->findURLsForCache();
        $response = $this->get('api_client')->get('/alpha/soccerseasons/398/fixtures');
        if ($response->getStatusCode() === 200) {
            //fetch the current matchday
            $completedMatchday = $this->getDoctrine()->getRepository('EatSleepCodeAdminBundle:Setting')->findOneByKey('completed_matchday');
            $latestMatchdayUpdated = null;
            foreach (json_decode($response->getBody())->fixtures as $fixture) {
                //only work on fixtures older than the current match day
                if ($fixture->matchday > $completedMatchday->getValue()) {
                    $existingFixture = $em->getRepository('EatSleepCodeAPIBundle:Fixture')->findOneBy(array('url' => $fixture->_links->self->href));
                    if ($existingFixture == null) {
                        $newFixture = new Fixture();
                        $newFixture->setMatchDay($fixture->matchday);
                        $newFixture->setDate($fixture->date);
                        $newFixture->setUrl($fixture->_links->self->href);
                        $newFixture->setHomeTeam($allTeams[$fixture->_links->homeTeam->href]);
                        $newFixture->setAwayTeam($allTeams[$fixture->_links->awayTeam->href]);
                        $newFixture->setHomeScore($fixture->result->goalsHomeTeam);
                        $newFixture->setAwayScore($fixture->result->goalsAwayTeam);
                        $em->persist($newFixture);
                    }
                    elseif ($fixture->result->goalsHomeTeam >= 0) { //match has been played
                        $existingFixture->setPlayed(true);
                        $existingFixture->setHomeScore($fixture->result->goalsHomeTeam);
                        $existingFixture->setAwayScore($fixture->result->goalsAwayTeam);
                        $em->persist($existingFixture);

                        //score any predictions for this fixture
                        $pointQueries = array(
                            'UPDATE EatSleepCodeAPIBundle:Prediction p SET p.points = 3 WHERE p.homeScore = :homeScore AND p.awayScore = :awayScore AND p.fixture = :fixture',
                            'UPDATE EatSleepCodeAPIBundle:Prediction p SET p.points = 1 WHERE ((p.homeScore > p.awayScore AND :homeScore > :awayScore) OR (p.homeScore < p.awayScore AND :homeScore < :awayScore) OR (p.homeScore = p.awayScore AND :homeScore = :awayScore)) AND p.fixture = :fixture AND p.points IS NULL',
                        );
                        foreach ($pointQueries as $q) {
                            $em->createQuery($q)->setParameters(array(
                                'homeScore' => $existingFixture->getHomeScore(),
                                'awayScore' => $existingFixture->getAwayScore(),
                                'fixture' => $existingFixture,
                            ))->execute();
                        }
                        $em->createQuery('UPDATE EatSleepCodeAPIBundle:Prediction p SET p.points = 0 WHERE p.fixture = :fixture AND p.points IS NULL')->setParameter('fixture', $existingFixture)->execute();

                        //this game has been played, so set the current week
                        $latestMatchdayUpdated = $existingFixture->getMatchDay();
                    }
                    elseif (strtotime($fixture->date) != $existingFixture->getDate()->getTimestamp()) { //update the date
                        $existingFixture->setDate($fixture->date);
                        $em->persist($existingFixture);
                    }
                }
            }
            if ($latestMatchdayUpdated != null) {
                $completedMatchday->setValue($latestMatchdayUpdated);
                $em->persist($completedMatchday);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('fixture_list'));
        }

        return new Response('Error', $response->getStatusCode(), array('X-Status-Code' => $response->getStatusCode()));
    }
}
