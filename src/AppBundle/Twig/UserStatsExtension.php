<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatsExtension extends \Twig_Extension
{
	protected $doctrine;

	public function __construct(RegistryInterface $doctrine)
	{
		$this->doctrine = $doctrine;
	}

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('user_stats', array($this, 'userStatsFilter'))
		);
	}

	public function userStatsFilter($user)
	{
		$following_repo = $this->doctrine->getRepository('MyNetworkBackendBundle:Following');
		$publication_repo = $this->doctrine->getRepository('MyNetworkBackendBundle:Publications');
		$like_repo = $this->doctrine->getRepository('MyNetworkBackendBundle:Likes');

		$user_following = $following_repo->findBy(['user' => $user]);
		$user_followers = $following_repo->findBy(['followed' => $user]);
		$user_pubications = $publication_repo->findBy(['user' => $user]);
		$user_likes = $like_repo->findBy(['user' => $user]);

		$result = [
			'following' => count($user_following),
			'followers' => count($user_followers),
			'publications' => count($user_pubications),
			'likes' => count($user_likes)
		];

		return $result;
	}

	public function getName()
	{
		return 'user_stats_extension';
	}
}